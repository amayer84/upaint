<?php
defined('TYPO3_MODE') || die();

/* register rendertypes for TCA */
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry']['1648645414'] = [
    'nodeName' => 'upaintpath',
    'priority' => 40,
    'class' => \Mattgold\Upaint\Form\Element\Path::class,
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry']['1648712840'] = [
    'nodeName' => 'upaintbutton',
    'priority' => 40,
    'class' => \Mattgold\Upaint\Form\Element\Button::class,
];

call_user_func(
    function () {
        /**
         * Include Frontend Plugins
         */
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Mattgold.Upaint',
            'Pi1',
            [
                'Frontend' => 'list'
            ],
            [
                'Frontend' => ''
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Mattgold.Upaint',
            'Ajaxhandler',
            [
                'Ajax' => 'show'
            ],
            [
                'Ajax' => ''
            ]
        );

        /**
         * PageTSConfig
         */
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:upaint/Configuration/TsConfig/Page.tsconfig">'
        );
    }
);
