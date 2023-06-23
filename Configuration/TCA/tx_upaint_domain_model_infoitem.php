<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:upaint/Resources/Private/Language/de.locallang_mod_import.xlf:infoitem',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title,long_description,category',
        'iconfile' => 'EXT:upaint/Resources/Public/Icons/tx_upaint_domain_model_infoitem.png'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, long_description, category',
    ],
    'types' => [
        '1' => ['showitem' => '--div--;Allgemein, l10n_parent, l10n_diffsource, title, category, sys_language_uid, --div--;Inhalt, fal_media_c, show_print_c, main_title, long_description, fal_media_d, show_print_d, imagepos_b, button_title, button_link'],
    ],
    'palettes' => [
        'paletteDate' => [
            'showitem' => 'date_type,datetime,duration,duration_unit,',
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ]
                ],
                'default' => 0,
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_upaint_domain_model_infoitem',
                'foreign_table_where' => 'AND tx_upaint_domain_model_infoitem.pid=###CURRENT_PID### AND tx_upaint_domain_model_infoitem.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ]
                ],
            ]
        ],
        'starttime' => [
            'exclude' => true,
            'behaviour' => [
                'allowLanguageSynchronization' => true
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'default' => 0,
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'behaviour' => [
                'allowLanguageSynchronization' => true
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
            ],
        ],
        'title' => [
            'exclude' => true,
            'label' => 'Überschrift',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'long_description' => [
            'exclude' => true,
            'label' => 'Inhalt',
            'config' => [
                'type' => 'text',
                'cols' => 30,
                'rows' => 5,
                'softref' => 'rtehtmlarea_images,typolink_tag,images,email[subst],url',
                'enableRichtext' => true,
                'richtextConfiguration' => 'unipassau_admin',
            ]
        ],
        'date_type' => [
            'label' => 'Typ',
            'onChange' => 'reload',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['Datum', 'absolute'],
                    ['Dauer', 'relative']
                ],
            ],
        ],
        'datetime' => [
            'exclude' => false,
            'displayCond' => 'FIELD:date_type:=:absolute',
            'label' => 'Datum',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 16,
                'eval' => 'date,int',
            ]
        ],
        'duration' => [
            'exclude' => true,
            'displayCond' => 'FIELD:date_type:=:relative',
            'label' => 'Dauer',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'int'
            ],
        ],
        'duration_unit' => [
            'label' => 'Einheit',
            'displayCond' => 'FIELD:date_type:=:relative',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['Tage', 'days'],
                    ['Wochen', 'weeks'],
                    ['Monate', 'months']
                ],
            ],
        ],
        'category' => [
            'exclude' => true,
            'label' => 'Kategorie',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_upaint_domain_model_category',
                'MM' => 'tx_upaint_infoitem_category_mm',
            ],
        ],
        'fal_media_c' => [
            'exclude' => true,
            'label' => 'Top-Bild (100% Breite)',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'fal_media_c',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
                    ],
                    'foreign_types' => [
                        '0' => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                    ],
                    'minitems' => 0,
                    'maxitems' => 1
                ],
                'png,jpg,jpeg,svg'
            ),
        ],
        'fal_media_d' => [
            'exclude' => true,
            'label' => 'Bild',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'fal_media_d',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
                    ],
                    'foreign_types' => [
                        '0' => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                    ],
                    'minitems' => 0,
                    'maxitems' => 1
                ],
                'png,jpg,jpeg,svg'
            ),
        ],
        'show_print_c' => [
            'exclude' => true,
            'label' => 'Bild in Druckansicht anzeigen?',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'show_print_d' => [
            'exclude' => true,
            'label' => 'Bild in Druckansicht anzeigen?',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'imagepos_b' => [
            'label' => 'Bildausrichtung',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['Links', 'left'],
                    ['Rechts', 'right']
                ],
            ],
        ],
        'main_title' => [
            'exclude' => true,
            'label' => 'Überschrift',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'button_title' => [
            'exclude' => true,
            'label' => 'Beschriftung (für die Darstellung als Button)',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'button_link' => [
            'exclude' => true,
            'label' => 'Link (für die Darstellung als Button)',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'size' => 30,
                'eval' => 'trim',
                'softref' => 'typolink',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
    ],
];
