<?php

/*
 * Copyright (C) 2020 PARAT Beteiligungs GmbH
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

namespace Pmwebdesign\Staffm\Task;

/**
 * Description of SyncCreatedUserAd
 *
 * @author Markus Blöchl <mbloechl@parat.eu>
 */
class SyncCreatedUserAd extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{
    /**
     * Checks if a manuell created user has an AD-Account and if yes, copy the 
     * data from the created in the AD and delete the created.
     * 
     * @return boolean
     */
    public function execute() {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
        
        $mitarbeiterRep = $objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\MitarbeiterRepository::class);
	$querySettings = $objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
	$querySettings->setRespectStoragePage(FALSE);
	$mitarbeiterRep->setDefaultQuerySettings($querySettings);
        
        $allUsers = $mitarbeiterRep->findAll();
        
        $createdUsers = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        foreach ($allUsers as $user) {
            $createdUsername = $user->getPersonalnummer() . substr($user->getFirstName(), 0, 2) . substr($user->getLastName(), 0, 2);
            if($user->getUsername() == $createdUsername) {
                $createdUsers->attach($user);
            }
        }
        
        foreach($allUsers as $userAll) {
            foreach($createdUsers as $created) {
                if($userAll->getPersonalnummer() == $created->getPersonalnummer() && $userAll->getUid() != $created->getUid() && strlen($userAll->getUsername()) == 4) {
                    // $userAll is the user from AD
                    // copy image
                    if(count($created->getImage()) > 0) {
                        $images = $created->getImage();
                        foreach($images as $image) {
                            $userAll->getImage()->attach($image);
                        }
                    }
                    // copy qualifications
                    if(count($created->getEmployeequalifications()) > 0) {
                        $quali = $created->getEmployeequalifications();
                        foreach($quali as $qua) {
                            $newQuali = new \Pmwebdesign\Staffm\Domain\Model\Employeequalification();
                            $newQuali->setQualification($qua->getQualification());
                            $newQuali->setStatus($qua->getStatus());
                            $newQuali->setTargetstatus($qua->getTargetstatus());
                            $newQuali->setEmployee($userAll);
                            $newQuali->setNote($qua->getNote());
                            $newQuali->setReminderDate($qua->getReminderDate());
                            $newQuali->setActivities($qua->getActivities());
                            $newQuali->setHistories($qua->getHistories());
                            $newQuali->setShowstatus($qua->getShowstatus());
                            $userAll->getEmployeequalifications()->attach($newQuali);
                        }
                    }
                    // copy app cost center
                    $userAll->setAppCostCenter($created->getAppCostCenter());
                    $userAll->setExpiryDate($created->getExpiryDate());
                    // copy changeforms
                    $changeforms = $created->getChangeforms();
                    foreach($changeforms as $change) {
                        $change->setEmployee($userAll);
                        $userAll->getChangeforms()->attach($change);
                    }
                    // copy last change form
                    $last = $created->getLastChangeform();
                    if($last != null) {
                        $last->setEmployee($userAll);
                        $userAll->setLastChangeform($last);
                    }
                    // copy penultimateChangeform
                    $penult = $created->getPenultimateChangeform();
                    if($penult != null) {
                        $penult->setEmployee($userAll);
                        $userAll->setPenultimateChangeform($penult);
                    }
                    // copy projects
                    $projectRep = $objectManager->get(\BmParat\Timerecording\Domain\Repository\ProjectRepository::class);
                    $allPro = $projectRep->findAll();
                    foreach($allPro as $pro) {
                        foreach($pro->getStaffs() as $staff) {
                            if($staff->getStaff()->getUid() == $created->getUid()) {
                                $staff->setStaff($userAll);
                            }
                        }
                        $projectRep->update($pro);
                    }
                    
                    // update AD user
                    $mitarbeiterRep->update($userAll);
                    
                    // set uid of userAll in absenceforms
                    $absenceformRep = $objectManager->get(\Pmwebdesign\Forms\Domain\Repository\AbsenceformRepository::class);
                    $allForms = $absenceformRep->findAll();
                    foreach($allForms as $form) {
                        if($form->getClaimant() != null) {
                            if($form->getClaimant()->getUid() == $created->getUid()) {
                                $form->setClaimant($userAll);
                                if($form->getCurrentemployee()->getUid() == $created->getUid()) {
                                    $form->setCurrentemployee($userAll);
                                }
                                foreach($form->getAbHistories() as $hist) {
                                    if($hist->getCurrentemployee()->getUid() == $created->getUid()) {
                                        $hist->setCurrentemployee($userAll);
                                    }
                                }
                                foreach($form->getNotices() as $notice) {
                                    if($notice->getEmployee()->getUid() == $created->getUid()) {
                                        $notice->setEmployee($userAll);
                                    }
                                }
                                $absenceformRep->update($form);
                            }
                        }
                    }
                    $mitarbeiterRep->remove($created);
                    $objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class)->persistAll();
                }
            }
        }
        return true;
    }
}
