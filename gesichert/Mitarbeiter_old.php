<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$GLOBALS['TCA']['tx_staffm_domain_model_mitarbeiter'] = array(
	'ctrl' => $GLOBALS['TCA']['tx_staffm_domain_model_mitarbeiter']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, personalnummer, username, date_of_birth, last_name, first_name, telephone, fax, handy, email, title, kostenstelle, firma, standort',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, personalnummer, username, date_of_birth, last_name, first_name, telephone, fax, handy, email, title, kostenstelle, firma, standort'),
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
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_staffm_domain_model_mitarbeiter',
				'foreign_table_where' => 'AND tx_staffm_domain_model_mitarbeiter.pid=###CURRENT_PID### AND tx_staffm_domain_model_mitarbeiter.sys_language_uid IN (-1,0)',
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
	
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		
		'personalnummer' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.personalnummer',
		'config' => array(
			'type' => 'input',
			'size' => 30,
			'eval' => 'trim'
			),
		),
		'username' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.username',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'date_of_birth' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.date_of_birth',
			'config' => array(
				'type' => 'input',
				'size' => 6,
				'eval' => 'timesec',
				'checkbox' => 1,
				'default' => time()
			)
		),
		'last_name' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.last_name',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'first_name' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.first_name',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'position' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.position',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_staffm_domain_model_position',
				'minitems' => 0,
				'maxitems' => 1,
			),
		),	
		'telephone' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.telephone',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'fax' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.fax',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'handy' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.handy',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'email' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.email',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'title' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'kostenstelle' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.kostenstelle',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_staffm_domain_model_kostenstelle',
				'minitems' => 0,
				'maxitems' => 1,
			),
		),
		'firma' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.firma',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_staffm_domain_model_firma',
				'minitems' => 0,
				'maxitems' => 1,
			),
		),
		'standort' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.standort',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_staffm_domain_model_standort',
				'minitems' => 0,
				'maxitems' => 1,
			),
		),
                /*'deleted' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.deleted',
			'config' => array(
				'type' => 'input',
				'size' => 2,
				'eval' => 'trim'
			),
		),*/		
	),
);
