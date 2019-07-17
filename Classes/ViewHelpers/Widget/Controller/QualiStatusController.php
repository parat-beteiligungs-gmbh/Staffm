<?php

/*
 * Copyright (C) 2019 pm-webdesign.eu 
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

namespace Pmwebdesign\Staffm\ViewHelpers\Widget\Controller;

use Pmwebdesign\Staffm\Utility\ArrayUtility;

/** 
 * Controller for Sorting
 */
class QualiStatusController extends \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController  {
    /**
     *      
     */
    protected $objects;
    
    /**
     * Property for a user who have authorizations (cost centers) and this must be checked in list
     *
     * @var String 
     */
    protected $property;
    
    /**
     * Qualification status who don´t show for normal users
     *
     * @var integer
     */
    protected $qualiStatusIgnore = 0;


    public function initializeAction() {
        $this->objects = $this->widgetConfiguration['objects']; // To access the objects from ViewHelper   
        $this->property = $this->widgetConfiguration['property'];
        
        /* @var $settingsUtility \Pmwebdesign\Staffm\Utility\SettingsUtility */
        $settingsUtility = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Utility\SettingsUtility::class);
        $this->qualiStatusIgnore = $settingsUtility->getQualiStatusIgnore();       
    }
    
    /**     
     * @param int $countmit counter for elements
     */
    public function indexAction($countmit = 0) {
        // Authorization for employee check available?
        if($this->property == "check") {
            // Get User with authorizations (cost centers)    
            /* @var $userService \Pmwebdesign\Staffm\Domain\Service\UserService */
            $userService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\UserService::class);    
            $user = $userService->getLoggedInUser();           
            if($user != NULL) {
                /* @var $mitarbeiterRepository \Pmwebdesign\Staffm\Domain\Repository\MitarbeiterRepository */
                $mitarbeiterRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Repository\MitarbeiterRepository::class);    
                $mitarbeiters = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();         
                $mitarbeiters = ArrayUtility::fillOjectStorageFromQueryResult($mitarbeiterRepository->findMitarbeiterVonVorgesetzten("", $user));                      
            }
        }
        $arrNeu = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
                
        foreach ($this->objects as $employeequalification) { 
            // Authorization for employee check available?
            if (($this->property == "check") && ($user != NULL) && ($mitarbeiters != NULL)) {             
                if (in_array($employeequalification->getEmployee(), $mitarbeiters->toArray(), TRUE)) {
                    $employeequalification->setShowstatus(TRUE);
                    $arrNeu->attach($employeequalification);
                // Check status
                } elseif ($employeequalification->getStatus() > $this->qualiStatusIgnore) {
                    $employeequalification->setShowstatus(FALSE);
                    $arrNeu->attach($employeequalification);
                }
            // Check status
            } elseif ($employeequalification->getStatus() > $this->qualiStatusIgnore) {
                $employeequalification->setShowstatus(FALSE);
                $arrNeu->attach($employeequalification);
            }
        }
       
        $modifiedObjects = $arrNeu;		

        // Count all objects
        $countmit = count($modifiedObjects);		

        // Transfer resorted Objects via contentArguments to the view
        $this->view->assign('contentArguments', array(
            $this->widgetConfiguration['as'] => $modifiedObjects,
            'countmit' => $countmit
        ));
        
    }
}