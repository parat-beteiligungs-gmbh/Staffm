<?php
return [
	'ctrl' => [
                    'title' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_history',
                    'label' => 'status',
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
                            /*'disabled' => 'hidden',
                            'starttime' => 'starttime',
                            'endtime' => 'endtime',*/
                    ],
                    'searchFields' => 'status, date_from, date_to, reminder_date, assessor',
                    'iconfile' => 'EXT:staffm/Resources/Public/Icons/tx_staffm_domain_model_history.gif'
        ],
	'interface' => [
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, status, date_from, date_to, reminder_date, assessor',
	],
	'types' => [
		'1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, status, date_from, date_to, reminder_date, assessor'],
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
				'foreign_table' => 'tx_staffm_domain_model_history',
				'foreign_table_where' => 'AND tx_staffm_domain_model_history.pid=###CURRENT_PID### AND tx_staffm_domain_model_history.sys_language_uid IN (-1,0)',
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
		'status' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_history.status',
			'config' => [
				'type' => 'input',
				'size' => 1,
				'eval' => 'trim'
			],
		],
                'date_from' => [
                    'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_history.date_from',
                    'config' => [
                        'dbType' => 'date',
                        'type' => 'input',
                        'renderType' => 'inputDateTime',
                        'eval' => 'date',
                    ],
                ],
                'date_to' => [
                    'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_history.date_to',
                    'config' => [
                        'dbType' => 'date',
                        'type' => 'input',
                        'renderType' => 'inputDateTime',
                        'eval' => 'date',
                    ],
                ],                
                'assessor' => [			
			'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_history.assessor',
			'config' => [
				'type' => 'select',
                                'renderType' => 'selectSingle',
				'foreign_table' => 'fe_users',
				'minitems' => 0,
				'maxitems' => 1,
			],
		],
	],
];
## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder