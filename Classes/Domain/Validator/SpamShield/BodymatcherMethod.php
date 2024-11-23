<?php
namespace WapplerSystems\PowermailBodymatcher\Domain\Validator\SpamShield;

use In2code\Powermail\Domain\Model\Mail;
use In2code\Powermail\Domain\Validator\SpamShield\AbstractMethod;
use TYPO3\CMS\Core\Utility\DebugUtility;

/**
 * Powermail SpamShield method for checking for spam based on regular expressions.
 * 
 * A mail will be marked as spam if its body matches a blacklisted pattern and NOT a whitelisted pattern.
 */
class BodymatcherMethod extends AbstractMethod
{

    // const PATTERN_EMOJI = '/([^-\p{L}\x00-\x7F]+)/u';

 

    protected $configuration = [
        'patterns_whitelist' => [],
        'patterns_blacklist' => [],
    ];


    public function getWhitelistedPatterns(): array
    {
        return $this->configuration['pattern_whitelist'] ?? [];
    }
    public function getBlacklistedPatterns(): array
    {
        return $this->configuration['pattern_blacklist'] ?? [];
    }

    public function getFieldAnswers(): array
    {
        return $this->arguments['field'];
    }

    protected function matchesBlacklistedPattern(string $text): bool
    {
        foreach ($this->getBlacklistedPatterns() as $pattern) {
            if (preg_match($pattern, $text)) {
                return true;
            }
        }
        return false;
    }  
    protected function matchesWhitelistedPattern(string $text): bool
    {
        foreach ($this->getWhitelistedPatterns() as $pattern) {
            if (preg_match($pattern, $text)) {
                return true;
            }
        }
        return false;
    }

    public function isSpam(string $text): bool
    {
        // TODO the whitelist is a bit buggy - a good URL can "disable" the blacklist entirely
        // check should be word-for-word, tokenize!
        return $this->matchesBlacklistedPattern($text) && !$this->matchesWhitelistedPattern($text);
    }

    /**
     * Will be called by Powermail - should return true if the mail is spam.
     */
    public function spamCheck(): bool
    {
        // DebugUtility::debug($this->getFieldAnswers(), header: 'Form Answers');
        // DebugUtility::debug($this->configuration, header: 'BodymatcherMethod Configuration');
        foreach($this->getFieldAnswers() as $fieldValue) {
            if ($this->isSpam($fieldValue))
                return true;
        }
        return false;   
    }
}
