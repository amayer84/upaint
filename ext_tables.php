<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(
    function () {

        /**
         * Backend Module
         */
        if (TYPO3_MODE === 'BE') {
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'Mattgold.Upaint',
                'web',
                'm1',
                '',
                [
                    'Backend' => 'categorylist, questionlist, infoitemlist, importer, exporter'
                ],
                [
                    'access' => 'user,group',
                    'icon' => 'EXT:upaint/Resources/Public/Icons/ModuleImport.svg',
                    'labels' => 'LLL:EXT:upaint/Resources/Private/Language/de.locallang_mod_import.xlf',
                ]
            );
        }

        /**
         * Register icons
         */
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Imaging\IconRegistry::class
        );
        $iconRegistry->registerIcon(
            'extension-upaint',
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            ['source' => 'EXT:upaint/ext_icon.png']
        );

        /* enable new content elements on  pages */
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_upaint_domain_model_category');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_upaint_domain_model_question');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_upaint_domain_model_answer');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_upaint_domain_model_infoitem');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_upaint_domain_model_node');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_upaint_domain_model_answerconfig');
    }
);