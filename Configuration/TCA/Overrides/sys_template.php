<?php
defined('TYPO3') or die();

call_user_func(function()
{
   $extensionKey = 'ws_powermail_bodymatcher';

   /**
    * Default TypoScript
    */
   \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
      $extensionKey,
      'Configuration/TypoScript',
      'Powermail - Spamshield BodyMatcher'
   );
});
