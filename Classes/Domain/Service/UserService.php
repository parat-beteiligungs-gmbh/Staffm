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

namespace Pmwebdesign\Staffm\Domain\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use Pmwebdesign\Staffm\Utility\ArrayUtility;

/**
 * User Services
 *
 * @author Markus Puffer (m.puffer@pm-webdesign.eu)
 */
class UserService
{  
    /**
     * Return the logged in User
     * 
     * @return array|\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter|NULL
     */
    public function getLoggedInUser() 
    {     
        $uid = $GLOBALS['TSFE']->fe_user->user['uid'];
        // Frontend User?
        if ($uid != null) {
            // Yes, Frontend User
            $user = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager')->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->findOneByUid($uid);
        } else {
            // No, Backend User
            $user = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager')->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->findOneByUsername($GLOBALS['BE_USER']->user['username']);
        }
        return $user;       
    }
    
    /**
     * Check Admin authorization of logged in user
     * 
     * @param String $gruppenEintragS Group authorizations
     * @return bool
     */
    public function isAdmin() : bool
    { 
        $gruppenUser = $this->getGroupsOfLoggedInUser();        
        $settingsUtility = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Utility\SettingsUtility::class);
        $gruppenEintragS = $settingsUtility->getAdmingroups();        
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
        $objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
       
        $users = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $aUsers = explode(",", $settingusers);
        foreach ($aUsers as $sUser) {           
            $user = $objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->findOneByUid(intval($sUser));
            $users->attach($user);
        }
        return $users;
    }
    
    /**
     * Get assigned groups of logged in user
     * 
     * @return string
     */
    public function getGroupsOfLoggedInUser()
    {        
        return $GLOBALS['TSFE']->fe_user->groupData['uid']; 
    }
    
    /**
     * Check assigned employees
     * 
     * @param \TYPO3\CMS\Extbase\Mvc\Request $request
     * @param \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager  
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter>
     */
    public function getEmployeesOfCheckBoxes(\TYPO3\CMS\Extbase\Mvc\Request $request, \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager)
    {
        if ($request->hasArgument('employees')) {           
            $employees = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();

            // Read checkboxes into array
            $emp = $request->getArgument('employees');

            // Set employees to array items
            foreach ($emp as $e) {
                /* @var $employee \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter */
                $employee = $objectManager->get(
                                'Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository'
                        )->findOneByUid($e);                
                $employees->attach($employee);
            }
            return $employees;
        }
    }
    
    /**
     * Check changes of updated employee
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $employee
     * @return boolean True if employee has changes
     */
    public function getChangeStatus(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $employee)
    {       
        $state = FALSE;
        if($employee->_isDirty("personalnummer") == TRUE)
            $state = TRUE;
        if($employee->_isDirty("username") == TRUE)
            $state = TRUE;        
        if($employee->_isDirty("lastName") == TRUE)
            $state = TRUE;
        if($employee->_isDirty("firstName") == TRUE)
            $state = TRUE;
        if($employee->_isDirty("title") == TRUE)
            $state = TRUE;
        if($employee->_isDirty("telephone") == TRUE)
            $state = TRUE;
        // Verifies that images have been changed after a Controller action.
        $imageState = ArrayUtility::checkImageCollections($employee->getImage(), $employee->_getCleanProperty("image"));
        if ($imageState == TRUE)
            $state = TRUE;    
        
        return $state;
    }
    
    /**
     * Get the representations
     * 
     * @param \TYPO3\CMS\Extbase\Mvc\Request $request
     * @param \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $aktEmployee
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Representation>
     */
    public function getRepresentations(\TYPO3\CMS\Extbase\Mvc\Request $request, \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager, \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $aktEmployee)
    {
        if ($request->hasArgument('employees')) {           
            $representations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();

            // Read checkboxes into array
            $emp = $request->getArgument('employees');

            // Set employees to array items
            foreach ($emp as $e) {
                /* @var $employee \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter */
                $employee = $objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\MitarbeiterRepository::class)->findOneByUid($e);                
                $representation = new \Pmwebdesign\Staffm\Domain\Model\Representation();
                $representation->setEmployee($aktEmployee);
                $representation->setDeputy($employee);
                $representations->attach($representation);
            }
            return $representations;
        }
    }
}
