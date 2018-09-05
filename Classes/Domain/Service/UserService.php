<?php

/*
 * Copyright (C) 2018 Markus Puffer (m.puffer@pm-webdesign.eu)
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

namespace Pmwebdesign\Staffm\Domain\Service;

/**
 * Description of UserService
 *
 * @author Markus Puffer (m.puffer@pm-webdesign.eu)
 */
class UserService extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{  
    /**
     * Return the logged in User
     * 
     * @return array|\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter|NULL
     */
    public function getLoggedInUser() 
    {     
        $user = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager')->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->findOneByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        return $user;       
    }
    
    /**
     * Check Admin authorization of logged in user
     * 
     * @param String $gruppenEintragS Group authorizations
     * @return bool
     */
    public function isAdmin(String $gruppenEintragS) : bool
    {        
        $gruppenUser = $GLOBALS['TSFE']->fe_user->groupData['uid'];        
        //$gruppenEintragS = $this->settings["admingroups"]; // TODO: $this->settings does not run  
        $gruppenEintrag = explode(",", $gruppenEintragS);       
        $admin = FALSE;
        foreach ($gruppenUser as $group) {
            // Group admin?
            if (in_array($group, $gruppenEintrag)) {
                $admin = TRUE;
                break;
            }
        }       
        return $admin;        
    }
    
    /**
     * Choosed Users of settings in flexform
     * 
     * @param String $settingusers
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter> 
     */
    public function getSettingUsers(String $settingusers) 
    {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
       
        $users = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $aUsers = explode(",", $settingusers);
        foreach ($aUsers as $sUser) {           
            $user = $objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->findOneByUid(intval($sUser));
            $users->attach($user);
        }
        return $users;
    }
}
