<?php
declare(strict_types=1);

namespace Mattgold\Upaint\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class FrontendUtility
 */
class FrontendUtility
{
    /**
     * @return string
     */
    public static function getPluginName(): string
    {
        $pluginName = 'tx_upaint_pi1';
        if (!empty(GeneralUtility::_GPmerged('tx_upaint_web_upaintm1'))) {
            $pluginName = 'tx_upaint_web_upaintm1';
        }
        return $pluginName;
    }

    /**
     * @return string
     */
    public static function getActionName(): string
    {
        $action = '';
        $plugin = self::getPluginName();
        $arguments = GeneralUtility::_GPmerged($plugin);
        if (!empty($arguments['action'])) {
            $action = $arguments['action'];
        }
        return $action;
    }
}