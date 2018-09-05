<?php
return array(
	'ctrl' => array(
                    'title' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_position',
                    'label' => 'bezeichnung',
                    'tstamp' => 'tstamp',
                    'crdate' => 'crdate',
                    'cruser_id' => 'cruser_id',
                    'dividers2tabs' => TRUE,
                    'versioningWS' => FALSE,
                    'transOrigPointerField' => 'l10n_parent',
                    'transOrigDiffSourceField' => 'l10n_diffsource',
                    //'delete' => 'deleted',
                    'enablecolumns' => array(
                            /*'disabled' => 'hidden',
                            'starttime' => 'starttime',
                            'endtime' => 'endtime',*/
                    ),
                    'searchFields' => 'bezeichnung,',
                    'iconfile' => 'EXT:staffm/Resources/Public/Icons/tx_staffm_domain_model_position.gif'
        ),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, bezeichnung',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, bezeichnung'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
	
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
                                'renderType' => 'selectSingle',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
                                'renderType' => 'selectSingle',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_staffm_domain_model_position',
				'foreign_table_where' => 'AND tx_staffm_domain_model_position.pid=###CURRENT_PID### AND tx_staffm_domain_model_position.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),

		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),
		'nummer' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_position.nummer',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),	
		'bezeichnung' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_position.bezeichnung',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),	
	),
);
## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder