<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_category',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => TRUE,
        'versioningWS' => FALSE,
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'enablecolumns' => [
        /* 'disabled' => 'hidden',
          'starttime' => 'starttime',
          'endtime' => 'endtime', */
        ],
        'searchFields' => 'name',
        'iconfile' => 'EXT:staffm/Resources/Public/Icons/tx_staffm_domain_model_category.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, name, qualifications, employees',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, name, qualifications, employees'],
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
                'foreign_table' => 'tx_staffm_domain_model_category',
                'foreign_table_where' => 'AND tx_staffm_domain_model_category.pid=###CURRENT_PID### AND tx_staffm_domain_model_category.sys_language_uid IN (-1,0)',
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
        'name' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_category.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'description' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_category.description',
            'config' => [
                'type' => 'text',
                'cols' => 30,
                'rows' => 5,
                'defaultExtras' => 'richtext[*]',
            ],
        ],
        'qualifications' => [
            //'exclude' => 1,        
            'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_category.qualifications',
            'config' => [
                    'type' => 'select',
                    'renderType' => 'selectMultipleSideBySide',
                    'foreign_table' => 'tx_staffm_domain_model_qualifikation',
                    'MM' => 'tx_staffm_qualifikation_category_mm',
                    //'MM_opposite_field' => 'category',
                    'foreign_table_where' => ' AND tx_staffm_domain_model_qualifikation.pid=###CURRENT_PID### ORDER BY tx_staffm_domain_model_qualifikation.bezeichnung ', 
                    //'foreign_sortby' => 'sorting',
//                    'multiple' => 1,
                    'minitems' => 0,
                    'maxitems' => 1000,
            ],
        ],       
        // TODO: TCA employees
        'employees' => [
            //'exclude' => 1,        
            'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_category.employees',
            'config' => [
                    'type' => 'select',
                    'renderType' => 'selectMultipleSideBySide',
                    'foreign_table' => 'fe_users',
                    'MM' => 'tx_staffm_employee_category_mm',
                    'MM_opposite_field' => 'employee',
                    //'foreign_table_where' => ' AND fe_users.pid=###CURRENT_PID### ORDER BY fe_users.username ', // TODO: employees not show with this line
                    //'foreign_sortby' => 'sorting',
//                    'multiple' => 1,
                    'minitems' => 0,
                    'maxitems' => 1000,
            ],
        ],       
    ],
];
## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder