<?php
defined('TYPO3_MODE') || die();

/**
 * Register Plugins
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('upaint', 'Pi1', 'Internationalisierung 2.0');

/**
 * Disable not needed fields in tt_content
 */
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['upaint_pi1']
    = 'select_key,pages,recursive';

/**
 * Include Flexform
 */
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['upaint_pi1'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'upaint_pi1',
    'FILE:EXT:upaint/Configuration/FlexForms/FlexFormPi1.xml'
);

// add import button
$fields = array (
    'tx_upaint_imported' => array (
        'exclude' => 1,
        'config' => array (
            'type' => 'user',
            'renderType' => 'upaintbutton'
        )
    )
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $fields);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
	'tt_content',
    '--div--;Importer,tx_upaint_imported'
);

?>