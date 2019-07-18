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

namespace Pmwebdesign\Staffm\Utility;

/**
 * Settings Utility
 *
 * @author Markus Puffer <m.puffer@pm-webdesign.eu>
 */
class SettingsUtility
{
    /**
     * All settings of extension
     *
     * @var \TYPO3\CMS\Extbase\Mvc\Controller\AbstractController
     */
    public static $SETTINGS;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\Extbase\\Object\\ObjectManager');
        $configurationManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager');
        SettingsUtility::$SETTINGS = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS);
    }
    
    /**
     * Get Admin Groups in Settings
     * 
     * @return string
     */
    public function getAdmingroups()
    {
        return SettingsUtility::$SETTINGS['admingroups'];
    }
    
    /**
     * Get Groups for qualification status in Settings
     * 
     * @return string
     */
    public function getAdminQualificationStatusGroups()
    {
        return SettingsUtility::$SETTINGS['qualistatusgroups'];
    }
    
    /**
     * Get the status for qualification who is ignored for normal users
     */
    public function getQualiStatusIgnore()
    {
        return SettingsUtility::$SETTINGS['qualistatusignore'];
    }
}
