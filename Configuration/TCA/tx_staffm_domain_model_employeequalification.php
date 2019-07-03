<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_employeequalification',
        'label' => 'qualification',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => TRUE,
        'versioningWS' => false,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        //'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            /*
              'starttime' => 'starttime',
              'endtime' => 'endtime', */
        ],
        'searchFields' => 'qualification, status, employee, note, reminder_date, activities, histories',
        'iconfile' => 'EXT:staffm/Resources/Public/Icons/tx_staffm_domain_model_employeequalification.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, qualification, status, targetstatus, employee, note, reminder_date, activities, histories',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, qualification, status, targetstatus, employee, note, reminder_date, activities, histories'],
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
                'foreign_table' => 'tx_staffm_domain_model_employeequalification',
                'foreign_table_where' => 'AND tx_staffm_domain_model_employeequalification.pid=###CURRENT_PID### AND tx_staffm_domain_model_employeequalification.sys_language_uid IN (-1,0)',
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
            ],
        ],
        'qualification' => [
            'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_employeequalification.qualification',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_staffm_domain_model_qualifikation',
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
            ],
        ],
        'status' => [
            'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_employeequalification.status',
            'config' => [
                'type' => 'input',
                'size' => 1,
                'eval' => 'trim',
            ],
        ],
        'targetstatus' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_history.targetstatus',
            'config' => [
                'type' => 'input',
                'size' => 1,
                'eval' => 'trim'
            ],
        ],
        'employee' => [
            //'exclude' => 1,
            'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_employeequalification.employee',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'fe_users',
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
            ],
        ],
        'note' => [
            'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_employeequalification.note',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
            ],
        ],
        'reminder_date' => [
            'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_employeequalification.reminder_date',
            'config' => [
                'dbType' => 'date',
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'date',
            ],
        ],
        'activities' => [
            //'exclude' => 1,        
            'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_employeequalification.activities',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_staffm_domain_model_activity',
                'foreign_field' => 'employeequalification',
                'minitems' => 0,
                'maxitems' => 1000,
            ],
        ],
        'histories' => [
            //'exclude' => 1,        
            'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_employeequalification.histories',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_staffm_domain_model_history',
                'foreign_field' => 'employeequalification',
                'minitems' => 0,
                'maxitems' => 1000,
            ],
        ],
    ],
];
## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder