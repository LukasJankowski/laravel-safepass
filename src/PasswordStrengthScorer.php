<?php

namespace LukasJankowski\SafePass;

class PasswordStrengthScorer
{
    /** @const float - Lower bound assumption of time to hash based on bcrypt / scrypt / PBKDF2 */
    const SINGLE_GUESS = 0.010;

    /** @const int - Assumed number of cores guessing in parallel */
    const NUM_ATTACKERS = 100;

    /** @const int - The exponent of the score calculation, which is considered very safe */
    const EXPONENT_CONSIDERED_SAFE = 8; // 10^8 seconds ~ 3 years

    /**
     * Score for a password's bits of entropy.
     * @param float $entropy - Entropy to score
     * @return float - The calculated score in percent
     */
    public function score($entropy)
    {
        return $this->estimateCrackingTime($entropy) / pow(10, self::EXPONENT_CONSIDERED_SAFE) * 100;
    }

    /**
     * Get average time in seconds to crack the password based on its entropy.
     * @param float $entropy - The entropy to calculate with
     * @return float - The estimated seconds
     */
    private function estimateCrackingTime($entropy)
    {
        return 0.5 * pow(2, $entropy) * (self::SINGLE_GUESS / self::NUM_ATTACKERS);
    }
}
