<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Pmwebdesign.' . $_EXTKEY,
        'Staffm', // Plugin
        [// Cacheable actions
            'Mitarbeiter' => 'list, listChoose, listChooseQuali, choose, show, new, edit, editUser, create, editKst, update, delete, deleteQuali, deleteCategories, deleteRepresentations, showKst, showVeraKst, setRepresentations, setRepresentationCostCenters, deleteRepresentationCostCenters, setEmployeePresentStatus, deleteImage, export',
            'Position' => 'list, show, choose, export, new, edit, create, update, delete, deletePosition',
            'Kostenstelle' => 'list, listChoose, show, choose, export, new, edit, create, update, delete, deleteKst, deleteKstVerantwortlicher',
            'Firma' => 'list, show, choose, export, new, edit, create, update, delete, deleteFirma',
            'Qualifikation' => 'list, show, edit, new, create, update, delete, export',
            'Qualilog' => 'list, show, choose, edit, new, create, update, delete',
            'Category' => 'list, show, new, edit, create, update, delete',
            'Activity' => 'create, delete',
            'Representation' => 'setDeputyActiveStatus, setQualificationAuthorizationStatus'
        ],
        [// Non-cacheable actions
            'Mitarbeiter' => 'list, listChoose, listChooseQuali, choose, show, new, edit, editUser, create, editKst, update, delete, deleteQuali, deleteCategories, deleteRepresentations, showKst, showVeraKst, setRepresentations, setRepresentationCostCenters, deleteRepresentationCostCenters, setEmployeePresentStatus, deleteImage, export',
            'Position' => 'list, show, choose, export, new, edit, create, update, delete, deletePosition',
            'Kostenstelle' => 'list, listChoose, show, choose, export, new, edit, create, update, delete, deleteKst, deleteKstVerantwortlicher',
            'Firma' => 'list, show, choose, export, new, edit, create, update, delete, deleteFirma',
            'Qualifikation' => 'list, show, edit, create, update, delete',
            'Qualilog' => 'choose, create, update, delete',
            'Category' => 'list, show, new, edit, create, update, delete',
            'Activity' => 'create, delete',
            'Representation' => 'setDeputyActiveStatus, setQualificationAuthorizationStatus'
        ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Pmwebdesign.' . $_EXTKEY,
        'Staffmvorg',
        [// Cacheable actions
            'Mitarbeiter' => 'edit, listVgs, list, show, editKst, update, showKst, showVeraKst, deleteImage, deleteQuali, deleteCategories, setRepresentations, setRepresentationCostCenters, setEmployeePresentStatus, deleteRepresentations, deleteRepresentationCostCenters, export',
            'Position' => 'list, show, choose',
            'Kostenstelle' => 'list, listChoose, show, choose, export',
            'Firma' => 'list, show, choose, export',
            'Qualifikation' => 'list, listVgs, show, chooselist, edit, new, create, update, delete, export, exportQualisEmployees',
            'Qualilog' => 'list, show, choose, edit, new, create, update, delete',
            'Category' => 'list, show, new, edit, create, update, delete',
            'Activity' => 'create, delete',
            'Representation' => 'setDeputyStatus, setQualificationAuthorizationStatus'
        ],
        [// Non-cacheable actions
            'Mitarbeiter' => 'edit, listVgs, list, show, editKst, update, showKst, showVeraKst, deleteImage, deleteQuali, deleteCategories, setRepresentations, setRepresentationCostCenters, setEmployeePresentStatus, deleteRepresentations, deleteRepresentationCostCenters, export',
            'Position' => 'list, show, choose',
            'Kostenstelle' => 'list, listChoose, show, choose, export',
            'Firma' => 'list, show, choose, export',
            'Qualifikation' => 'list, listVgs, show, chooselist, edit, new, create, update, delete, export, exportQualisEmployees',
            'Qualilog' => 'list, show, choose, edit, new, create, update, delete',
            'Category' => 'list, show, new, edit, create, update, delete',
            'Activity' => 'create, delete',
            'Representation' => 'setDeputyStatus, setQualificationAuthorizationStatus'
        ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Pmwebdesign.' . $_EXTKEY,
        'Staffmcustom',
        [// Cacheable actions
            'Mitarbeiter' => 'listCustom, showCustom',
        ],
        [// Non-cacheable actions
            'Mitarbeiter' => 'listCustom, showCustom',
        ]
);

TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Pmwebdesign.' . $_EXTKEY,
        'Createuser',
        [
            'Mitarbeiter' => 'listCreate, editCreate, saveUserData, listCreated, removeUsername, assignNewMail, removeMail, assignNewPassword, createNewUser, getAllCostCenters, getAllPositions, getAllCompanies, createUser, editCreated, updateCreatedUser, listAllUser, updateAppCostCenter, listAllUserModal'
        ],
        [
            'Mitarbeiter' => 'listCreate, editCreate, saveUserData, listCreated, removeUsername, assignNewMail, removeMail, assignNewPassword, createNewUser, getAllCostCenters, getAllPositions, getAllCompanies, createUser, editCreated, updateCreatedUser, listAllUser, updateAppCostCenter, listAllUserModal'
        ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter('Pmwebdesign\\Staffm\\Property\\TypeConverter\\UploadedFileReferenceConverter');
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter('Pmwebdesign\\Staffm\\Property\\TypeConverter\\ObjectStorageConverter');

// Task for memories
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Pmwebdesign\Staffm\Task\SendMemories'] = [
    'extension' => $_EXTKEY,
    'title' => 'Send Memories',
    'description' => 'Send memories of employee qualification if the memory date is come',
];

// Task for set employee classes
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Pmwebdesign\Staffm\Task\SetEmployeeClasses'] = [
    'extension' => $_EXTKEY,
    'title' => 'Set employee classes',
    'description' => 'Check superiors and so on and set the class to the employees',
];

// Task for synchronize the created user with a ad acc
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Pmwebdesign\Staffm\Task\SyncCreatedUserAd'] = [
    'extension' => $_EXTKEY,
    'title' => 'Sync created user with AD',
    'description' => 'Check if the created user has an ad acc, if yes, sync data with it.',
];

/**
 * Add cache configuration
 */
if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['staffm_mycache'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['staffm_mycache'] = array();
}
if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['staffm_mycache']['frontend'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['staffm_mycache']['frontend'] = 'TYPO3\\CMS\\Core\\Cache\\Frontend\\VariableFrontend';
}
//if( !isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['staffm_mycache']['frontend'] ) ) {
//    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['staffm_mycache']['frontend'] = 'TYPO3\\CMS\\Core\\Cache\\Frontend\\StringFrontend';
//}
// When backend active -> cache isn´t save in cf_staffm_mycache
//if( !isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['staffm_mycache']['backend'] ) ) {
//    $TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['staffm_mycache']['backend'] = 'TYPO3\\CMS\\Core\\Cache\\Backend\\SimpleFileBackend';
//}
if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['staffm_mycache']['options'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['staffm_mycache']['options'] = array('defaultLifetime' => 0);
}
if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['staffm_mycache']['groups'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['staffm_mycache']['groups'] = array('pages');
}