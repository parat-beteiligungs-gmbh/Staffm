<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_qualifikation',
        'label' => 'bezeichnung',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => TRUE,
        'versioningWS' => FALSE,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        //'delete' => 'deleted',
        'enablecolumns' => [
        /* 'disabled' => 'hidden',
          'starttime' => 'starttime',
          'endtime' => 'endtime', */
        ],
        'searchFields' => 'bezeichnung, beschreibung, categories,',
        'iconfile' => 'EXT:staffm/Resources/Public/Icons/tx_staffm_domain_model_qualifikation.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, bezeichnung, beschreibung, employeequalifications, categories',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, bezeichnung, beschreibung, employeequalifications, categories'],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1],
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0]
                ],
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_staffm_domain_model_qualifikation',
                'foreign_table_where' => 'AND tx_staffm_domain_model_qualifikation.pid=###CURRENT_PID### AND tx_staffm_domain_model_qualifikation.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ]
        ],
        'bezeichnung' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_qualifikation.bezeichnung',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'beschreibung' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_qualifikation.beschreibung',
            'config' => [
                'type' => 'text',
                'cols' => 30,
                'rows' => 5,
                'defaultExtras' => 'richtext[*]',
            ],
        ],
        'employeequalifications' => [
            //'exclude' => 1,        
            'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_qualifikation.employeequalifications',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_staffm_domain_model_employeequalification',
                'foreign_field' => 'qualification',
                'foreign_label' => 'employee',
                //'foreign_sortby' => 'status, employee.last_name', // TODO: Error if employee.last_name
//                'foreign_sortby' => 'status',
                'minitems' => 0,
                'maxitems' => 1000,
            ],
        ],
        'categories' => [
            //'exclude' => 1,        
            'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_qualifikation.categories',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'multiple' => 1,
                'foreign_table' => 'tx_staffm_domain_model_category',
                'MM' => 'tx_staffm_qualifikation_category_mm',
                'MM_opposite_field' => 'category',
                'foreign_table_where' => ' AND tx_staffm_domain_model_category.pid=###CURRENT_PID### ORDER BY tx_staffm_domain_model_category.name ',
                'multiple' => 1,
                'minitems' => 0,
                'maxitems' => 1000,
            ],
        ],
        'assigned_trainings' => [
            'exclude' => 1,
            'label' => 'Trainings',
            'config' => [
                    'type' => 'select',
                    'multiple' => 1,
                    'foreign_table' => 'tx_staffm_domain_model_globaltraining',
                    'MM' => 'tx_staffm_qualification_training_mm',
                    'MM_opposite_field' => 'assigned_trainings',
                    'foreign_table_where' => ' AND tx_staffm_domain_model_globaltraining.pid=###CURRENT_PID### ORDER BY tx_staffm_domain_model_globaltraining.name',
                    'minitems' => 0,
                    'maxitems' => 99,
            ],
        ]
    ],
];
## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder