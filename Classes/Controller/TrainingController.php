<?php

/*
 * Copyright (C) 2021 PARAT Beteiligungs GmbH
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

namespace Pmwebdesign\Staffm\Controller;

/**
 * Description of TrainingController
 *
 * @author Markus Blöchl <mbloechl@parat.eu>
 */
class TrainingController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController 
{
    public function newAction()
    {
        // Load all qualifications
        $qualiRep = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\QualifikationRepository::class);
        $qualis = $qualiRep->findAll();
        $this->view->assign('qualis', $qualis);
        
        // Load all employees of the superior
        $userUid = $GLOBALS['TSFE']->fe_user->user['uid'];
        $allCostCenters = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\KostenstelleRepository::class)->findAll();
        $employees = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        foreach($allCostCenters as $center) {
            if($center->getVerantwortlicher() !== null && $center->getVerantwortlicher()->getUid() === $userUid) {
                $employees->addAll($center->getMitarbeiters());
            }
        }
        $this->view->assign('employees', $employees);
        
        $this->view->assign('menuname', "Schulung");
        
        // Load messages for alerts
        $noName = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_staffm_training.no.name', 'Staffm');
        $this->view->assign('noName', $noName);
                
        $noScheduledDate = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_staffm_training.no.scheduled.date', 'Staffm');
        $this->view->assign('noScheduledDate', $noScheduledDate);
                
        $falseQuali = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_staffm_training.false.quali', 'Staffm');
        $this->view->assign('falseQuali', $falseQuali);
        
        $employeeAlreadyAdded = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_staffm_training.employee.already.added', 'Staffm');
        $this->view->assign('employeeAlreadyAdded', $employeeAlreadyAdded);
                
        $addedEmployees = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_staffm_training.added.members', 'Staffm');
        $this->view->assign('addedEmployees', $addedEmployees);
        
        $qualiAlreadyAdded = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_staffm_training.qualification.already.added', 'Staffm');
        $this->view->assign('qualiAlreadyAdded', $qualiAlreadyAdded);
    }
    
    /**
     * Creates a new notice.
     * 
     * @return string
     */
    public function addNewNoticeAction()
    {
        $employeeUid = $this->request->getArgument("employeeUid");
        $noticeText = $this->request->getArgument("notice");
        $noticeUids = $this->request->getArgument("noticesUids");
        
        $employee = $this->objectManager->get(\Pmwebdesign\Forms\Domain\Repository\EmployeeRepository::class)->findByUid($employeeUid);
        
        $notice = new \Pmwebdesign\Forms\Domain\Model\Notice();
        $notice->setText($noticeText);
        $notice->setEmployee($employee);
        $notice->setDate(new \DateTime());
        $notice->setCanBeEdited(true);
        
        $this->objectManager->get(\Pmwebdesign\Forms\Domain\Repository\NoticeRepository::class)->add($notice);
        
        $persistenceManager = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class);
        $persistenceManager->persistAll();
        
        $uid = strval($notice->getUid());
        if($noticeUids == '') {
            return $uid;
        } else {
            $allUids = strval($noticeUids) . "," . $uid;
            return $allUids;
        }
    }
    
    /**
     * Loads the notice table.
     */
    public function noticeTableAction()
    {
        $noticesString = $this->request->getArgument("uids");
        if($noticesString != "") {
            $uidArray = explode(",", $noticesString);
            $allNotices = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
            $noticeRep = $this->objectManager->get(\Pmwebdesign\Forms\Domain\Repository\NoticeRepository::class);
            foreach($uidArray as $uid) {
                $notice = $noticeRep->findByUid($uid);
                $allNotices->attach($notice);
            }
            $this->view->assign("notices", $allNotices);
        } else {
            $this->view->assign("notices", new \TYPO3\CMS\Extbase\Persistence\ObjectStorage());
        }
        
        $currentUser = $this->objectManager->get(\Pmwebdesign\Forms\Domain\Repository\EmployeeRepository::class)->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $this->view->assign('user', $currentUser);
    }
    
    /**
     * Removes a notice.
     * 
     * @return string
     */
    public function removeNoticeAction()
    {
        $uids = $this->request->getArgument("noticesUids");
        $toDeleteUid = $this->request->getArgument("noticeUid");
        
        // remove notice
        $noticeRep = $this->objectManager->get(\Pmwebdesign\Forms\Domain\Repository\NoticeRepository::class);
        $notice = $noticeRep->findByUid($toDeleteUid);
        $noticeRep->remove($notice);
        
        $persistenceManager = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class);
        $persistenceManager->persistAll();
        
        // remove from string
        $uidsArray = explode(",", $uids);
        $newUids = "";
        $lastElement = end($uidsArray);
        foreach($uidsArray as $uid) {
            if($uid != $toDeleteUid && $uid != $lastElement) {
                $newUids .= $uid . ",";
            } elseif($uid == $lastElement && $uid != $toDeleteUid) {
                $newUids .= $uid;
            }
        }
        $lastChar = substr($newUids, -1);
        if($lastChar == ',') {
            $newUids = substr($newUids, 0, -1);
        }
        return $newUids;
    }
    
    /**
     * Updates a notice.
     * 
     * @return string
     */
    public function updateNoticeAction()
    {
        $noticeUid = $this->request->getArgument("noticeUid");
        $text = $this->request->getArgument("text");
        
        $noticeRep = $this->objectManager->get(\Pmwebdesign\Forms\Domain\Repository\NoticeRepository::class);
        $notice = $noticeRep->findByUid($noticeUid);
        $notice->setText($text);
        $notice->setDate(new \DateTime());
        
        $noticeRep->update($notice);
        
        $persistenceManager = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class);
        $persistenceManager->persistAll();
        
        return "Success";
    }
    
    public function createAction() 
    {
        // Load all data
        $name = $this->request->getArgument('name');
        $scheduled = $this->request->getArgument('scheduledDate');
        $quali = $this->request->getArgument('qualis');
        $members = $this->request->getArgument('members');
        $noticesUids = $this->request->getArgument('noticesUid');
        
        // create new training
        $training = new \Pmwebdesign\Staffm\Domain\Model\Training();
        // set name
        $training->setName($name);
        //set date
        $date = date_create_from_format('d-m-Y', $scheduled);
        $training->setScheduledDate($date);
        // set qualis
        if($quali != '') {
            // remove first comma
            $quali = ltrim($quali, ',');
            $qualiArray = explode(',', $quali);
            $qualiRep = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\QualifikationRepository::class);
            $assignedQualis = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
            foreach($qualiArray as $q) {
                $assignedQualis->attach($qualiRep->findByUid($q));
            }
            $training->setAssignedQualis($assignedQualis);
        } else {
            $training->setAssignedQualis(new \TYPO3\CMS\Extbase\Persistence\ObjectStorage());
        }
        // set responsible
        $currentUser = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\MitarbeiterRepository::class)->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $training->setResponsible($currentUser);
        // set notices
        if($noticesUids == '') {
            $training->setNotices(new \TYPO3\CMS\Extbase\Persistence\ObjectStorage());
        } else {
            $noticesArray = explode(",", $noticesUids);
            $notices = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
            $noticeRep = $this->objectManager->get(\Pmwebdesign\Forms\Domain\Repository\NoticeRepository::class);
            foreach($noticesArray as $noticeId) {
                $notice = $noticeRep->findByUid($noticeId);
                $notice->setCanBeEdited(false);
                $notices->attach($notice);
            }
            $training->setNotices($notices);
        }
        
        // set members
        if($members == '') {
            $training->setMembers(new \TYPO3\CMS\Extbase\Persistence\ObjectStorage());
        } else {
            $memberArray = explode(',', $members);
            $membersStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
            $mitarbeiterRep = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\MitarbeiterRepository::class);
            foreach($memberArray as $memberId) {
                $member = $mitarbeiterRep->findByUid($memberId);
                $membersStorage->attach($member);
            }
            $training->setMembers($membersStorage);
        }
        
        // create history
        $history = new \Pmwebdesign\Forms\Domain\Model\History();
        $history->setEntrytime(new \DateTime());
        $history->setActaction("Schulung angelegt");
        $history->setCurrentEmployee($currentUser);
        $histories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $histories->attach($history);
        $training->setHistories($histories);
        
        // save training
        $trainingRep = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\TrainingRepository::class);
        $trainingRep->add($training);
        $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class)->persistAll();
        
        $this->forward('list');
    }
    
    /**
     * Lists all trainings of the logged in responsible.
     */
    public function listAction()
    {
        // Load trainings
        $trainingRep = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\TrainingRepository::class);
        $trainings = $trainingRep->findByResponsible($GLOBALS['TSFE']->fe_user->user['uid']);
        $notCanceled = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        // only not canceled and not accomplished
        foreach($trainings as $training) {
            if(!$training->getCanceled() && $training->getAccomplishedDate() == null) {
                $notCanceled->attach($training);
            }
        }
        $this->view->assign('trainings', $notCanceled);
        
        $this->view->assign('menuname', "Schulung");
    }
    
    public function showAction()
    {
        //Load training
        $trainingRep = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\TrainingRepository::class);
        $trainingUid = $this->request->getArgument('trainingUid');
        $training = $trainingRep->findByUid($trainingUid);
        $this->view->assign('training', $training);
        
        $this->view->assign('menuname', "Schulung");
        
        if($training->getCanceled()) {
            $this->view->assign('lastAction', 'canceled');
        } else {
            if($training->getAccomplishedDate() != null) {
                $this->view->assign('lastAction', 'accomplished');
            } else {
                $this->view->assign('lastAction', 'list');
            }
        }
        
        $show = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_staffm_show', 'Staffm');
        $hide = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_staffm_hide', 'Staffm');
        $this->view->assign('show', $show);
        $this->view->assign('hide', $hide);
    }
    
    public function editAction()
    {
        //Load training
        $trainingRep = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\TrainingRepository::class);
        $trainingUid = $this->request->getArgument('trainingUid');
        $training = $trainingRep->findByUid($trainingUid);
        $this->view->assign('training', $training);
        
        // Load notices uids
        $notices = $training->getNotices();
        if(count($notices) > 0) {
            $noticesString = $notices[0]->getUid();
            for($i = 1; $i < count($notices); $i++) {
                $noticesString .= "," . $notices[$i]->getUid();
            }
            $this->view->assign("noticeString", $noticesString);
        }
        
        // Load effect uid
        if($training->getEffect() !== null) {
            $this->view->assign('effectUid', $training->getEffect()->getUid());
        }
        
        // Load all qualifications
        $qualiRep = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\QualifikationRepository::class);
        $qualis = $qualiRep->findAll();
        $this->view->assign('qualis', $qualis);
        $assignedQualis = $training->getAssignedQualis();
        if(count($assignedQualis) > 0) {
            for($i = 0; $i < count($assignedQualis); $i++) {
                $qualisString .= "," . $assignedQualis[$i]->getUid();
            }
            $this->view->assign("qualisString", $qualisString);
        }
        
        // Load all employees of the superior
        $userUid = $GLOBALS['TSFE']->fe_user->user['uid'];
        $allCostCenters = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\KostenstelleRepository::class)->findAll();
        $employees = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        foreach($allCostCenters as $center) {
            if($center->getVerantwortlicher() !== null && $center->getVerantwortlicher()->getUid() === $userUid) {
                $employees->addAll($center->getMitarbeiters());
            }
        }
        $this->view->assign('employees', $employees);
        $memberUids = ',';
        foreach($training->getMembers() as $employee) {
            $memberUids .= $employee->getUid() . ",";
        }
        $memberUids = substr($memberUids, 0, -1);
        $this->view->assign('memberUids', $memberUids);
        
        // Load all effects
        $effects = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\EffectRepository::class)->findAll();
        $this->view->assign('effects', $effects);
        
        // Load messages to set with javascript
        $this->view->assign('menuname', "Schulung");
        $show = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_staffm_show', 'Staffm');
        $hide = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_staffm_hide', 'Staffm');
        $noDate = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_staffm_enter.accomplished.date', 'Staffm');
        $change = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_staffm_change', 'Staffm');
        $employeeAlreadyAdded = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_staffm_training.employee.already.added', 'Staffm');
        $addedEmployees = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_staffm_training.added.members', 'Staffm');
        $noName = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_staffm_training.no.name', 'Staffm');
        $falseQuali = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_staffm_training.false.quali', 'Staffm');
        $reason = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_staffm_training.reason.shift.message', 'Staffm');
        $reasonNotice = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_staffm_training.reason.shift', 'Staffm');
        $cancelTraining = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_staffm_really.training.cancel', 'Staffm');
        $canceled = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_staffm_training.canceled', 'Staffm');
        $cancelReason = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_staffm_training.cancel.reason', 'Staffm');
        $effectReason = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_staffm_training.reason.effect', 'Staffm');
        $saved = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_staffm_training.saved', 'Staffm');
        $qualiAlreadyAdded = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_staffm_training.qualification.already.added', 'Staffm');
        $this->view->assign('qualiAlreadyAdded', $qualiAlreadyAdded);
        $this->view->assign('show', $show);
        $this->view->assign('hide', $hide);
        $this->view->assign('noDate', $noDate);
        $this->view->assign('change', $change);
        $this->view->assign('employeeAlreadyAdded', $employeeAlreadyAdded);
        $this->view->assign('addedEmployees', $addedEmployees);
        $this->view->assign('noName', $noName);
        $this->view->assign('falseQuali', $falseQuali);
        $this->view->assign('reasonMessage', $reason);
        $this->view->assign('reasonNotice', $reasonNotice);
        $this->view->assign('cancelTraining', $cancelTraining);
        $this->view->assign('canceled', $canceled);
        $this->view->assign('cancelReason', $cancelReason);
        $this->view->assign('effectReason', $effectReason);
        $this->view->assign('saved', $saved);
    }
    
    public function updateAction()
    {
        // Get data from request
        $trainingUid = $this->request->getArgument('training');
        $name = $this->request->getArgument('name');
        $scheduledDate = $this->request->getArgument('scheduledDate');
        $accomplished = $this->request->getArgument('accomplished');
        $effectUid = $this->request->getArgument('effectUid');
        $quali = $this->request->getArgument('qualis');
        $employees = $this->request->getArgument('employees');
        $noticesUids = $this->request->getArgument('notices');
        
        $training = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\TrainingRepository::class)->findByUid($trainingUid);
        $currentUser = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\MitarbeiterRepository::class)->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        
        // update name
        if($training->getName() !== $name) {
            $training->setName($name);
            $history = new \Pmwebdesign\Forms\Domain\Model\History();
            $history->setEntrytime(new \DateTime());
            $history->setActaction("Bezeichnung geändert");
            $history->setCurrentEmployee($currentUser);
            $training->getHistories()->attach($history);
        }
        
        // update scheduled date
        if(date_create_from_format('d-m-Y', $scheduledDate) !== false) {
            if($scheduledDate !== $training->getScheduledDate()->format('d-m-Y')) {
                // training was shifted
                $training->setNumberShifts($training->getNumberShifts() + 1);
                $training->setScheduledDate(date_create_from_format('d-m-Y', $scheduledDate));
                $history = new \Pmwebdesign\Forms\Domain\Model\History();
                $history->setEntrytime(new \DateTime());
                $history->setActaction("Schulung wurde verschoben");
                $history->setCurrentEmployee($currentUser);
                $training->getHistories()->attach($history);
            }
        } else {
            // training was canceled
            $training->setCanceled(true);
            $history = new \Pmwebdesign\Forms\Domain\Model\History();
            $history->setEntrytime(new \DateTime());
            $history->setActaction("Schulung wurde abgesagt");
            $history->setCurrentEmployee($currentUser);
            $training->getHistories()->attach($history);
        }
        
        // update accomplished date
        // check if there is a date
        if(date_create_from_format('d-m-Y', $accomplished) !== false) {
            // accomplished is a date
            // check if date is changed
            if($training->getAccomplishedDate() == null || $accomplished !== $training->getAccomplishedDate()->format('d-m-Y')) {
                // accomplished date was changed
                $training->setAccomplishedDate(date_create_from_format('d-m-Y', $accomplished));
                $effect = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\EffectRepository::class)->findByUid($effectUid);
                $training->setEffect($effect);
                $history = new \Pmwebdesign\Forms\Domain\Model\History();
                $history->setEntrytime(new \DateTime());
                $history->setActaction("Schulung wurde durchgeführt");
                $history->setCurrentEmployee($currentUser);
                $training->getHistories()->attach($history);
            }
        }
        
        // update notices
        if($noticesUids !== '') {
            $noticesArray = explode(",", $noticesUids);
            $notices = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
            $noticeRep = $this->objectManager->get(\Pmwebdesign\Forms\Domain\Repository\NoticeRepository::class);
            foreach($noticesArray as $noticeId) {
                $notice = $noticeRep->findByUid($noticeId);
                $notice->setCanBeEdited(false);
                $notices->attach($notice);
            }
            $training->setNotices($notices);
        }
        // update members
        if($employees == '') {
            $training->setMembers(new \TYPO3\CMS\Extbase\Persistence\ObjectStorage());
        } else {
            $employees = substr($employees, 1);
            $memberArray = explode(',', $employees);
            $membersStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
            $mitarbeiterRep = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\MitarbeiterRepository::class);
            foreach($memberArray as $memberId) {
                $member = $mitarbeiterRep->findByUid($memberId);
                $membersStorage->attach($member);
            }
            $training->setMembers($membersStorage);
        }
        
        // update quali
        if($quali != '') {
            $oldQualis = $training->getAssignedQualis();
            $assignedQualis = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
            $quali = substr($quali, 1);
            $qualiArray = explode(",", $quali);
            foreach($qualiArray as $qualiUid) {
                $qualification = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\QualifikationRepository::class)->findByUid($qualiUid);
                $assignedQualis->attach($qualification);
            }
            
            $training->setAssignedQualis($assignedQualis);
            // Check if something changed
            $oldUids = "";
            $newUids = "";
            foreach($oldQualis as $old) {
                $oldUids .= $old->getUid();
            }
            foreach($assignedQualis as $new) {
                $newUids .= $new->getUid();
            }
            if($oldUids != $newUids) {
                $history = new \Pmwebdesign\Forms\Domain\Model\History();
                $history->setEntrytime(new \DateTime());
                $history->setActaction("Qualifikationen wurden geändert");
                $history->setCurrentEmployee($currentUser);
                $training->getHistories()->attach($history);
            }
        }
        
        // save training
        $trainingRep = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\TrainingRepository::class);
        $trainingRep->add($training);
        $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class)->persistAll();
        
        $this->forward('list');
    }
    
    public function listCanceledAction()
    {
        // Load trainings
        $trainingRep = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\TrainingRepository::class);
        $trainings = $trainingRep->findByResponsible($GLOBALS['TSFE']->fe_user->user['uid']);
        $canceled = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        // only canceled
        foreach($trainings as $training) {
            if($training->getCanceled()) {
                $canceled->attach($training);
            }
        }
        $this->view->assign('trainings', $canceled);
        
        $this->view->assign('menuname', "Schulung");
    }
    
    public function listAccomplishedAction()
    {
        // Load trainings
        $trainingRep = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\TrainingRepository::class);
        $trainings = $trainingRep->findByResponsible($GLOBALS['TSFE']->fe_user->user['uid']);
        $accomplished = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        // only accomplished
        foreach($trainings as $training) {
            if($training->getAccomplishedDate() != null) {
                $accomplished->attach($training);
            }
        }
        $this->view->assign('trainings', $accomplished);
        
        $this->view->assign('menuname', "Schulung");
    }
}
