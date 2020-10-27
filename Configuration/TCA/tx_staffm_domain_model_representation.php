<?php

/*
 * Copyright (C) 2018 pm-webdesign.eu 
 * Markus Puffer <m.puffer@pm-webdesign.eu>
 *
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 */

$GLOBALS['TCA']['tx_staffm_domain_model_representation'] = [
    'ctrl' => [
        'title' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_representation',
        'label' => 'employee',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => TRUE,
        'versioningWS' => FALSE,
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'searchFields' => 'employee, deputy, costcenters, qualification_authorization',
        'iconfile' => 'EXT:staffm/Resources/Public/Icons/tx_staffm_domain_model_representation.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, employee, deputy, costcenters, status_active, qualification_authorization',
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
                'foreign_table' => 'tx_staffm_domain_model_representation',
                'foreign_table_where' => 'AND tx_staffm_domain_model_representation.pid=###CURRENT_PID### AND tx_staffm_domain_model_representation.sys_language_uid IN (-1,0)',
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
        'employee' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_representation.employee',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'fe_users',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'deputy' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_representation.deputy',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'fe_users',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'costcenters' => [
            //'exclude' => 1,        
            'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_representation.costcenters',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',  
                'foreign_table' => 'tx_staffm_domain_model_kostenstelle',
                'MM' => 'tx_staffm_representation_kostenstelle_mm',
                //'foreign_table_where' => ' AND tx_staffm_domain_model_kostenstelle.pid=###CURRENT_PID### ORDER BY tx_staffm_domain_model_kostenstelle.bezeichnung ',
                'foreign_table_where' => ' AND tx_staffm_domain_model_kostenstelle.verantwortlicher=###REC_FIELD_employee### ', // List actually employee cost centers
                'minitems' => 0,
                'maxitems' => 1000               
            ],       
        ],
        'status_active' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang.xlf:tx_staffm_domain_model_representation.status_active',
            'config' => [
                'type' => 'check',
                'items' => [
                    [0, 1],                    
                ],
            ]
        ],
        'qualification_authorization' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang.xlf:tx_staffm_domain_model_representation.qualification_authorization',
            'config' => [
                'type' => 'check',
                'items' => [
                    [0, 1],                    
                ],
            ]
        ],
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, employee, deputy, costcenters, status_active, qualification_authorization'],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
];
