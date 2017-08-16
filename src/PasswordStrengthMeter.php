<?php

namespace LukasJankowski\SafePass;

use ZxcvbnPhp\Matcher;
use ZxcvbnPhp\Searcher;

class PasswordStrengthMeter
{
    /** @var PasswordStrengthScorer - For calculating the score of the password */
    private $scorer;

    /** @var Searcher - For calculating the minimum entropy the password has */
    private $searcher;

    /** @var Matcher -  For checking the password against common attack vectors / weaknesses */
    private $matcher;

    /**
     * PasswordStrengthMeter constructor.
     * @param PasswordStrengthScorer $scorer - For calculating the score of the password
     * @param Searcher $searcher - For calculating the minimum entropy the password has
     * @param Matcher $matcher - For checking the password against common attack vectors / weaknesses
     */
    public function __construct(PasswordStrengthScorer $scorer, Searcher $searcher, Matcher $matcher)
    {
        $this->scorer = $scorer;
        $this->searcher = $searcher;
        $this->matcher = $matcher;
    }

    /**
     * Evaluate the password strength.
     * @param string $attribute - The name of the attribute to check
     * @param string $password - The password to check
     * @param array $parameters - The passed parameters from the validation rule
     * @param \Illuminate\Validation\Validator $validator - The instance of the validator
     * @return bool - Whether the password passed the strength test or not
     */
    public function validate($attribute, $password, $parameters, $validator)
    {
        return (head($parameters) ?: 50) <= $this->evaluate($password);
    }

    /**
     * Evaluate the percentage the password reached
     * @param string $password - The password to check
     * @return float - The percentage the password reached while evaluating
     */
    private function evaluate($password)
    {
        return strlen($password) === 0
            ? 0.00
            : $this->scorer->score(
                $this->searcher->getMinimumEntropy(
                    $password,
                    $this->matcher->getMatches($password)
                )
            );
    }
}
