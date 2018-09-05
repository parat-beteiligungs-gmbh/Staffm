<?php
return array(
	'columns' => array(
		'mitarbeiter' => array(
			//'exclude' => 1,
			'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter',
			'config' => array(
				'type' => 'select',
                                'renderType' => 'selectSingle',
                                'foreign_table' => 'fe_users',				
                                'size' => 1,
                                'minitems' => 0,
				'maxitems' => 1,
			),
		),
		'qualifikation' => array(
			//'exclude' => 1,
			'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_qualifikation',
			'config' => array(
				'type' => 'select',
                                'renderType' => 'selectSingle',
				'foreign_table' => 'tx_staffm_domain_model_qualifikation',
                                'size' => 1,
                                'minitems' => 0,
				'maxitems' => 1,
			),
		),
		
	),
);
## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder