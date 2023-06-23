<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:upaint/Resources/Private/Language/de.locallang_mod_import.xlf:answerconfig',
        'label' => 'answer',
        'label_userFunc' => 'Mattgold\Upaint\Utility\EditorUtility->answerTitle',
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
        'hideTable' => 1,
        'searchFields' => 'answer',
        'iconfile' => 'EXT:upaint/Resources/Public/Icons/tx_upaint_domain_model_question.png'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, answer',
    ],
    'types' => [
        '1' => ['showitem' => 'l10n_parent, l10n_diffsource, answer, infoitems, nextnode'],
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
                'foreign_table' => 'tx_upaint_domain_model_answerconfig',
                'foreign_table_where' => 'AND tx_upaint_domain_model_answerconfig.pid=###CURRENT_PID### AND tx_upaint_domain_model_answerconfig.sys_language_uid IN (-1,0)',
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
        'answer' => [
            'exclude' => true,
            'label' => 'Wähle eine Antwortmöglichkeit aus:',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'itemsProcFunc' => 'Mattgold\Upaint\Utility\EditorUtility->loadAnswers',
            ]
        ],
        'infoitems' => [
            'exclude' => true,
            'label' => 'Mit welchen Info-Items soll die Antwort verknüpft werden?',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'itemsProcFunc' => 'Mattgold\Upaint\Utility\EditorUtility->loadInfoitems',
            ]
        ],
        'nextnode' => [
            'exclude' => true,
            'label' => 'Welche Frage soll im Anschluss angezeigt werden?',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['Keine weitere Frage anzeigen', 0],
                ],
                'foreign_table' => 'tx_upaint_domain_model_node',
                'foreign_table_where' => 'AND tx_upaint_domain_model_node.parentid IN (SELECT tx_upaint_domain_model_node.parentid FROM tx_upaint_domain_model_node WHERE tx_upaint_domain_model_node.uid = ###REC_FIELD_node###)',
            ]
        ],
        /*
        'nextnode' => [
            'exclude' => true,
            'label' => 'Welche Frage soll im Anschluss angezeigt werden?',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'itemsProcFunc' => 'Mattgold\Upaint\Utility\EditorUtility->getNextQuestion',
            ]
        ],*/
        'node' => [
            'exclude' => true,
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
    ],
];
