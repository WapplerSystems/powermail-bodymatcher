<?php
namespace WapplerSystems\PowermailBodyMatcher\Domain\Validator\Spamshield;

use In2code\Powermail\Domain\Validator\SpamShield\AbstractMethod;

/**
 * Powermail SpamShield method for checking for spam based on regular expressions.
 * 
 * A mail will be marked as spam if its body matches a blacklisted pattern and NOT a whitelisted pattern.
 */
class BodyMatcherMethod extends AbstractMethod
{

    protected $configuration = [
        "patterns_blacklist" => [
            "/https?:\\/\\/[A-Za-z0-9]+/i",
        ],
        "patterns_whitelist" => [
            "/https:\\/\\/pooldoktor.at/i",
        ]
    ];



    protected function matchesBlacklistedPattern(string $text): bool
    {
        foreach ($this->configuration['patterns_blacklist'] as $pattern) {
            if (preg_match($pattern, $text)) {
                return true;
            }
        }
        return false;
    }  
    protected function matchesWhitelistedPattern(string $text): bool
    {
        foreach ($this->configuration['patterns_whitelist'] as $pattern) {
            if (preg_match($pattern, $text)) {
                return true;
            }
        }
        return false;
    }

    public function isSpam(string $text): bool
    {
        return $this->matchesBlacklistedPattern($text) && !$this->matchesWhitelistedPattern($text);
    }

    /**
     * Will be called by Powermail - should return true if the mail is spam.
     */
    public function spamCheck(): bool
    {
        $mail = $this->mail;
        return $this->isSpam($mail->getBody());
    }
}
