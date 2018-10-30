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

/**
 * Qualification Services
 *
 * @author Markus Puffer <m.puffer@pm-webdesign.eu>
 */
class QualificationService
{
    /**
     * Check assigned qualifications with status and notes for employees
     * 
     * @param \TYPO3\CMS\Extbase\Mvc\Request $request
     * @param \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Employeequalification>
     */
    public function getEmployeequalifications(\TYPO3\CMS\Extbase\Mvc\Request $request, \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager, \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter)
    {
        if ($request->hasArgument('qualifikationen')) {              
            $employeequalifications = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
            
            // Read checkboxes into array
            $qua = $request->getArgument('qualifikationen');            
            
            // Read previous qualifications of employee            
            $prevEmployeequalifications = $mitarbeiter->getEmployeequalifications();
                        
            // Read status
            if($request->hasArgument('qualificationsstatus')) {
                $qualificationsstatus = $request->getArgument('qualificationsstatus');                
            }
            // Read notes
            if($request->hasArgument('qualificationsnotes')) {
                $qualificationsnotes = $request->getArgument('qualificationsnotes');
            }
            // Read reminder dates
            if($request->hasArgument('qualificationsreminderdate')) {
                $qualificationsreminderdate = $request->getArgument('qualificationsreminderdate');
            }
            
            // Get logged in user
            $userService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\UserService::class);
            $assessor = $userService->getLoggedInUser();
            
            // Set qualifications to array items
            foreach ($qua as $q) {    
                $prevStatus = FALSE;
                $employeequalification = new \Pmwebdesign\Staffm\Domain\Model\Employeequalification();
                $qualification = $objectManager->get(
                        'Pmwebdesign\\Staffm\\Domain\\Repository\\QualifikationRepository'
                )->findOneByUid($q);   
                $status = $qualificationsstatus[$qualification->getUid()];
                $note = $qualificationsnotes[$qualification->getUid()];
                $reminderDate = $qualificationsreminderdate[$qualification->getUid()];
                
                // Check if previous qualification exist
                $histories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
                foreach ($prevEmployeequalifications as $prevEmployeequalification) {
                    if($prevEmployeequalification->getQualification() === $qualification) {
                        $prevStatus = TRUE;
                        // Qualification exist, just update
                        if($status != null) {
                            $histories = $prevEmployeequalification->getHistories();                            
                            // Status changed or no histories?
                            if($prevEmployeequalification->getStatus() <> $status || count($histories) <= 0) {                                
                                // Status has changed?
                                if($prevEmployeequalification->getStatus() <> $status) {                                   
                                    // Yes, status has changed   
                                    // Check history
                                    foreach ($histories as $history) {
                                        // Old status?
                                        if($history->getStatus() == $prevEmployeequalification->getStatus()) {
                                            // Set End Date                                        
                                            $history->setDateTo(new \DateTime());                                            
                                        }
                                    }
                                    // Set new status
                                    $prevEmployeequalification->setStatus($status);      
                                }                               
                                // Add new history
                                $newHistory = new \Pmwebdesign\Staffm\Domain\Model\History();
                                $newHistory->setStatus($status);
                                $newHistory->setDateFrom(new \DateTime());
                                
                                $newHistory->setAssessor($assessor);
                                $histories->attach($newHistory);
                                $prevEmployeequalification->setHistories($histories);
                            }                            
                        }
                        if($note != null) {
                            $prevEmployeequalification->setNote($note);
                        }                        
                        break;
                    }
                }
                
                // Previous employeequalification found?   
                if($prevStatus == FALSE) {
                    // No, set new employeequalification
                    $employeequalification->setEmployee($mitarbeiter);
                    $employeequalification->setQualification($qualification);
                    // Status?
                    if($status != null) {
                        $employeequalification->setStatus($status);
                    }
                    // Notice?
                    if($note != null) {
                        $employeequalification->setNote($note);
                    }
                    // Reminder date?
                    if($reminderDate != null) {
                        $employeequalification->setReminderDate($reminderDate);
                    }
                    // Add new history               
                    $newHistory = new \Pmwebdesign\Staffm\Domain\Model\History();
                    $newHistory->setStatus($status);
                    $newHistory->setDateFrom(new \DateTime());                    
                    $newHistory->setAssessor($assessor);
                    $histories->attach($newHistory);
                    $employeequalification->setHistories($histories);
                    $employeequalifications->attach($employeequalification);				
                } else {
                    // Yes, update previous employeequalification                    
                    // Notice?
                    if($note != null) {
                        $prevEmployeequalification->setNote($note);
                    } else {
                        $prevEmployeequalification->setNote("");
                    }
                    // Reminder date?
                    if($reminderDate != null) {
                        $prevEmployeequalification->setReminderDate($reminderDate);
                    } else {
                        $prevEmployeequalification->setReminderDate(NULL);
                    }
                    $employeequalifications->attach($prevEmployeequalification);				
                }
            }                     
            return $employeequalifications;
        }
    }
}