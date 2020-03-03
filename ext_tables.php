<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

/**
 * Registers a general Module for Frontendusers
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'Pmwebdesign.' . $_EXTKEY,
	'Staffm',   // Plugin
	'Stafflist' // Plugin-Titel
);

/**
 * Registers a Frontend Module for supervisors
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'Pmwebdesign.' . $_EXTKEY,
	'Staffmvorg',   // Plugin
	'Stafflistvorg' // Plugin-Titel
);

/**
 * Registers a Frontend Module for custom employees
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'Pmwebdesign.' . $_EXTKEY,
	'Staffmcustom',   // Plugin
	'Stafflistcustom' // Plugin-Titel
);

call_user_func(
    function()
    {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('send_memories', 'Configuration/Typoscript', 'Send Memories');
    }
);

if (TYPO3_MODE === 'BE') {

	/**
	 * Registers a Backend Module
	 */
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'Pmwebdesign.' . $_EXTKEY,
		'web',              // Make module a submodule of 'web'
		'staffmbackend',    // Submodule key (Name of the module)
		'',                 // Position
		array( // Allowed controller action combinations
			'Mitarbeiter' => 'list, listVgs, show, showKst, showVeraKst, choose, edit, editKst, new, create, update, delete, deleteImage, export',
			'Position' => 'list, show, choose, edit, new, create, update, delete, deletePosition, export',
			'Kostenstelle' => 'list, show, choose, edit, new, create, update, delete, deleteKst, deleteKstVerantwortlicher, export',
			'Firma' => 'list, show, choose, edit, new, create, update, delete, deleteFirma, export',
			'Qualifikation' => 'list, show, choose, edit, new, create, update, delete, export',
			'Mitarbeiterqualifikation' => 'list, show, choose, edit, new, create, update, delete, deleteQuali'
		),                
		array( // Additional configuration
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_staffmbackend.xlf',
		)
	);        
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Staffmanage');

//\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('fe_users',$tmp_staffm_columns);

//$GLOBALS['TCA']['fe_users']['types']['Tx_Staffm_Mitarbeiter']['showitem'] = $TCA['fe_users']['types']['0']['showitem'];
//$GLOBALS['TCA']['fe_users']['types']['Tx_Staffm_Mitarbeiter']['showitem'] .= ',--div--;LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:tx_staffm_domain_model_mitarbeiter,';
//$GLOBALS['TCA']['fe_users']['types']['Tx_Staffm_Mitarbeiter']['showitem'] .= 'personalnummer, username, date_of_birth, last_name, first_name, position, telephone, fax, handy, email, title, kostenstelle, firma, standort, deleted';
//
//$GLOBALS['TCA']['fe_users']['columns'][$TCA['fe_users']['ctrl']['type']]['config']['items'][] = array('LLL:EXT:staffm/Resources/Private/Language/locallang_db.xlf:fe_users.tx_extbase_type.Tx_Staffm_Mitarbeiter','Tx_Staffm_Mitarbeiter');
//
//\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('fe_users', $GLOBALS['TCA']['fe_users']['ctrl']['type'],'','after:' . $TCA['fe_users']['ctrl']['label']);

/* Flexform für Frontend Plugin Staffm */
$pluginSignature = 'staffm_staffm';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_staffm.xml');

/* Flexform für Frontend Plugin Staffmvorg */
$pluginSignature2 = 'staffm_staffmvorg';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature2] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature2, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_staffm.xml');

/* Flexform für Frontend Plugin Staffmcustom */
$pluginSignature3 = 'staffm_staffmcustom';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature3] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature3, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_staffm_custom.xml');