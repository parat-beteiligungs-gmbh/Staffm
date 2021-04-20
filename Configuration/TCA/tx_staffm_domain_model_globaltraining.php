<?php

/* 
 * Copyright (C) 2021 PARAT Beteiligungs GmbH
 * Markus Blöchl <mbloechl@parat.eu>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
return [
    'ctrl' => [
        'title' => 'Global Training',
        'label' => 'name',
        'type' => 'record_type',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => TRUE,
        'versioningWS' => false,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'name, scheduled_date, accomplished_date, notices, histories, members, number_shifts, assigned_qualis',
        'iconfile' => 'EXT:staffm/Resources/Public/Icons/tx_staffm_domain_model_employeequalification.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, name, scheduled_date, accomplished_date, notices, histories, members, number_shifts, assigned_qualis, canceled',
    ],
    'types' => [
        '0' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, name, scheduled_date, accomplished_date, notices, histories, members, number_shifts, assigned_qualis, record_type, canceled'],
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, name, scheduled_date, accomplished_date, notices, histories, members, number_shifts, assigned_qualis, record_type, canceled, responsible, effect']
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
        'name' => [
            'label' => 'Name',
            'config' => [
                'type' => 'input',
                'size' => 1,
                'eval' => 'trim',
            ],
        ],
        'scheduled_date' => [
            'label' => 'ScheduledDate',
            'config' => [
                'dbType' => 'date',
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'date',
                'default' => null
            ],
        ],
        'accomplished_date' => [
            'label' => 'AccomplishedDate',
            'config' => [
                'dbType' => 'date',
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'date',
                'default' => null
            ],
        ],
        'notices' => [       
            'label' => 'Notices',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_forms_domain_model_notice',
                'foreign_field' => 'assigned_training',
                'minitems' => 0,
                'maxitems' => 1000,
            ],
        ],
        'histories' => [       
            'label' => 'Histories',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_forms_domain_model_history',
                'foreign_field' => 'assigned_training',
                'minitems' => 0,
                'maxitems' => 1000,
            ],
        ],
        'members' => [       
            'label' => 'Members',
            'config' => [
                'type' => 'select',
                'multiple' => 1,
                'foreign_table' => 'fe_users',
                'MM' => 'tx_staffm_mitarbeiter_training_mm',
                'foreign_table_where' => ' AND fe_users.pid=###CURRENT_PID### ORDER BY fe_users.name ',
                'minitems' => 0,
                'maxitems' => 99,
            ],
        ],
        'number_shifts' => [
            'exclude' => 1,
            'label' => 'NumberOfShifts',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'assigned_qualis' => [
            'label' => 'AssignedQualification',
            'config' => [
                'type' => 'select',
                'multiple' => 1,
                'foreign_table' => 'tx_staffm_domain_model_qualifikation',
                'MM' => 'tx_staffm_qualification_training_mm',
                'foreign_table_where' => ' AND tx_staffm_domain_model_qualifikation.pid=###CURRENT_PID### ORDER BY tx_staffm_domain_model_qualifikation.bezeichnung ',
                'minitems' => 0,
                'maxitems' => 99,
            ],
        ],
        'effect' => [
            'label' => 'Effect',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_staffm_domain_model_effect',
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
            ],
        ],
        'responsible' => [
            'label' => 'Responsible',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'fe_users',
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
            ],
        ],
        'record_type' => [
            'label' => 'Domain Object',
            'config' => [
                'type' => 'select',
                'items' => [
                    ['GlobalTraining', '0'],
                    ['Training', '1'],
                ],
                'default' => '0'
            ],
        ],
        'canceled' => [
            'exclude' => 1,
            'label' => 'canceled',
            'config' => [
                'type' => 'check',
            ],
        ]
    ],
];
