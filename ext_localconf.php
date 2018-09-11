<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Pmwebdesign.' . $_EXTKEY,
	'Staffm',   // Plugin
	array(
		'Mitarbeiter' => 'list, listChoose, listChooseQuali, choose, show, new, edit, editUser, create, editKst, update, delete, deleteQuali, showKst, showVeraKst, deleteImage, export',
		'Position' => 'list, show, choose, export, new, edit, create, update, delete, deletePosition',
		'Kostenstelle' => 'list, show, choose, export, new, edit, create, update, delete, deleteKst, deleteKstVerantwortlicher',
		'Firma' => 'list, show, choose, export, new, edit, create, update, delete, deleteFirma',
		'Standort' => 'list, show, choose, new, edit, create, update, delete, deleteStandort',		
		'Qualifikation' => 'list, show, choose, edit, new, create, update, delete, export',
		'Qualilog' => 'list, show, choose, edit, new, create, update, delete',
		'Mitarbeiterqualifikation' => 'list, show, choose, deleteQuali'
	),
	// non-cacheable actions
	array(
		'Mitarbeiter' => 'list, listChoose, listChooseQuali, choose, show, new, edit, editUser, create, editKst, update, delete, deleteQuali, deleteImage, export',
		'Position' => 'list, show, choose, export, new, edit, create, update, delete, deletePosition',
		'Kostenstelle' => 'list, show, choose, export, new, edit, create, update, delete, deleteKst, deleteKstVerantwortlicher',
		'Firma' => 'list, show, choose, export, new, edit, create, update, delete, deleteFirma',
		'Standort' => 'list, show, choose, new, edit, create, update, delete, deleteStandort',		
		'Qualifikation' => 'list, show, choose, create, update, delete',
		'Qualilog' => 'choose, create, update, delete',
		'Mitarbeiterqualifikation' => 'choose, deleteQuali'
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Pmwebdesign.' . $_EXTKEY,
	'Staffmvorg',
	array( // cacheable actions
		'Mitarbeiter' => 'listVgs, show, edit, editKst, update, showKst, showVeraKst, deleteImage, deleteQuali, export',
                'Position' => 'list, show, choose',
		'Kostenstelle' => 'list, show, choose, export',
		'Firma' => 'list, show, choose, export',
		'Qualifikation' => 'list, listVgs, show, choose, chooselist, edit, new, create, update, delete, export',
		'Qualilog' => 'list, show, choose, edit, new, create, update, delete',
		'Mitarbeiterqualifikation' => 'list, show, choose, chooselist'
	),	
	array( // non-cacheable actions
		'Mitarbeiter' => 'listVgs, show, edit, editKst, update, showKst, showVeraKst, deleteImage, deleteQuali, export',	
                'Position' => 'list, show, choose',
		'Kostenstelle' => 'list, show, choose, export',
		'Firma' => 'list, show, choose, export',
		'Qualifikation' => 'choose, chooselist, create, update, delete',
		'Qualilog' => 'choose, create, update, delete',
		'Mitarbeiterqualifikation' => 'choose'
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Pmwebdesign.' . $_EXTKEY,
	'Staffmcustom',
	array( // cacheable actions
		'Mitarbeiter' => 'listCustom, showCustom',             
	),	
	array( // non-cacheable actions
		'Mitarbeiter' => 'listCustom, showCustom',
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter('Pmwebdesign\\Staffm\\Property\\TypeConverter\\UploadedFileReferenceConverter');
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter('Pmwebdesign\\Staffm\\Property\\TypeConverter\\ObjectStorageConverter');

// Show Sheduler Task
//$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['TYPO3\Staffm\Task\Mitarbeitersynch'] = array (
//	'extension' => $_EXTKEY,
//	'title' => 'Mitarbeiter-Synchronisation',
//	'description' => 'Synchronisiert fehlende Daten zu Mitarbeitern',
//);

/**
 * Add cache configuration
 */
if( !is_array( $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['staffm_mycache'] ) ) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['staffm_mycache'] = array();
}
if( !isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['staffm_mycache']['frontend'] ) ) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['staffm_mycache']['frontend'] = 'TYPO3\\CMS\\Core\\Cache\\Frontend\\VariableFrontend';
}
//if( !isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['staffm_mycache']['frontend'] ) ) {
//    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['staffm_mycache']['frontend'] = 'TYPO3\\CMS\\Core\\Cache\\Frontend\\StringFrontend';
//}
// When backend active -> cache isn´t save in cf_staffm_mycache
//if( !isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['staffm_mycache']['backend'] ) ) {
//    $TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['staffm_mycache']['backend'] = 'TYPO3\\CMS\\Core\\Cache\\Backend\\SimpleFileBackend';
//}
if( !isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['staffm_mycache']['options'] ) ) {
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['staffm_mycache']['options'] = array( 'defaultLifetime' => 0 );
}
if( !isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['staffm_mycache']['groups'] ) ) {
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['staffm_mycache']['groups'] = array( 'pages' );
}