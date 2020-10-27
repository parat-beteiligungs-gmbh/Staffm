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

if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

// Configure new fields 
$fields = [
    'personalnummer' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.personalnummer',
        'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
        ],
    ],
    'handy' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.handy',
        'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
        ],
    ],
    'date_of_birth_show' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.date_of_birth_show',
        'config' => [
                'type' => 'check',                
                'default' => false
        ],
    ],
    'date_of_birth' => [
        'exclude' => 1,      
        'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.date_of_birth',
        'config' => [
                'type' => 'input', 
                'renderType' => 'inputDateTime',
                'size' => 13,                
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                        'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
                'allowLanguageSynchronization' => true,
        ],
    ],
    'kostenstelle' => [
        'exclude' => 1,        
        'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.kostenstelle',
        'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_staffm_domain_model_kostenstelle',
                'minitems' => 0,
                'maxitems' => 1,
        ],
    ],
    'firma' => [
        'exclude' => 1,        
        'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.firma',
        'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_staffm_domain_model_firma',
                'minitems' => 0,
                'maxitems' => 1,
        ],
    ],
    'position' => [
        'exclude' => 1,        
        'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.position',
        'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_staffm_domain_model_position',
                'minitems' => 0,
                'maxitems' => 1,
        ],
    ],
    'employeequalifications' => [
        'exclude' => 0,        
        'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.employeequalifications',
        'config' => [
                'type' => 'inline',                                
                'foreign_table' => 'tx_staffm_domain_model_employeequalification',                
                'foreign_field' => 'employee',
                'foreign_label' => 'qualification',            
                'minitems' => 0,
                'maxitems' => 1000,                
        ],
    ],
    'image' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.image',
        'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('image', [
            'appearance' => [
                'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:images.addFileReference'
            ],
            // custom configuration for displaying fields in the overlay/reference table
            // to use the imageoverlayPalette instead of the basicoverlayPalette
            'foreign_match_fields' => [
                'fieldname' => 'image',
                //'tablenames' => 'tx_staffm_domain_model_mitarbeiter',
                'tablenames' => 'fe_users',
                'table_local' => 'sys_file',
            ],            
            'foreign_types' => [
                '0' => [
                    'showitem' => '
                        --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                        --palette--;;filePalette'
                ],               
                \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                    'showitem' => '
                        --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                        --palette--;;filePalette'
                ]
            ]
        ], 'jpg')
    ],
    'categories' => [
        'exclude' => 1,        
        'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.categories',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectMultipleSideBySide',                      
            'foreign_table' => 'tx_staffm_domain_model_category',
            'MM' => 'tx_staffm_employee_category_mm',
//            'MM_opposite_field' => 'category',
//            'foreign_table_where' => ' AND tx_staffm_domain_model_category.pid=###CURRENT_PID### ORDER BY tx_staffm_domain_model_category.name ',            
            'minitems' => 0,
            'maxitems' => 1000,
            'wizards' => [
                '_PADDING' => 1,
                '_VERTICAL' => 1,
                'edit' => [
                    'module' => [
                        'name' => 'wizard_edit',
                    ],
                    'type' => 'popup',
                    'title' => 'LLL:EXT:staffm/Resources/Private/Language/locallang.xlf:staffm.edit', 
                    'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_edit.gif',
                    'popup_onlyOpenIfSelected' => 1,
                    'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
                ],
                'add' => [
                    'module' => [
                        'name' => 'wizard_add',
                    ],
                    'type' => 'script',
                    'title' => 'LLL:EXT:staffm/Resources/Private/Language/locallang.xlf:staffm.add',
                    'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_add.gif',
                    'params' => [
                        'table' => 'tx_staffm_domain_model_category',
                        'pid' => '###CURRENT_PID###',
                        'setValue' => 'prepend'
                    ],
                ],
            ],
        ],
    ],
    'representations' => [
        'exclude' => 0,        
        'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.representations',
        'config' => [
                'type' => 'inline',                                
                'foreign_table' => 'tx_staffm_domain_model_representation',                
                'foreign_field' => 'employee',
                'foreign_label' => 'deputy',                           
                'minitems' => 0,
                'maxitems' => 100,   
                
        ],
    ],
    'assigned_representations' => [
        'exclude' => 0,        
        'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.assignedRepresentations',
        'config' => [
                'type' => 'inline',                                
                'foreign_table' => 'tx_staffm_domain_model_representation',                
                'foreign_field' => 'deputy',
                'foreign_label' => 'employee',                           
                'minitems' => 0,
                'maxitems' => 100,   
                
        ],
    ], 
    'present' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter.present',
        'config' => [
            'type' => 'check',
            'items' => [
                [0, 1],                    
            ],
        ],
    ]
];

// Add new fields to fe_users
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('fe_users', $fields, 1);
      
// Make fields visible in the TCEforms:
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
  'fe_users', // Table name
  'personalnumber, handy, date_of_birth_show, date_of_birth, kostenstelle, firma, position, employeequalifications, categories, representations, assigned_representations;;;;1-1-1'
);
  
// Add the new palette:
//$GLOBALS['TCA']['pages']['palettes']['tx_pagesaddfields'] = [
//  'showitem' => 'tx_pagesaddfields_customcheckbox,tx_pagesaddfields_customtext'
//);