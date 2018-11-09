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

namespace Pmwebdesign\Staffm\Controller; // Class must exist in composer.json under ps4

use PHPOffice\PhpSpreadsheet\Spreadsheet;
use PHPOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PHPOffice\PhpSpreadsheet\Writer\Xlsx;
use Pmwebdesign\Staffm\Domain\Model\Firma;
use Pmwebdesign\Staffm\Domain\Model\Kostenstelle;
use Pmwebdesign\Staffm\Domain\Model\Mitarbeiter;
use Pmwebdesign\Staffm\Domain\Model\Position;
use Pmwebdesign\Staffm\Domain\Model\Qualifikation;
use Pmwebdesign\Staffm\Domain\Repository\MitarbeiterRepository;
use Pmwebdesign\Staffm\Domain\Repository\QualifikationRepository;
use Pmwebdesign\Staffm\Domain\Service\CacheService;
use Pmwebdesign\Staffm\Domain\Service\QualificationService;
use Pmwebdesign\Staffm\Domain\Service\UserService;
use Pmwebdesign\Staffm\Property\TypeConverter\UploadedFileReferenceConverter;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration;

/**
 * Employee Controller
 * 
 * @author Markus Puffer (m.puffer@pm-webdesign.eu)
 */
class MitarbeiterController extends ActionController
{

    /**
     * Caching Framework
     *
     * @var CacheManager     
     */
    protected $cache;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter>
     */
    protected $objects;

    /**
     * mitarbeiterRepository
     * 
     * @var MitarbeiterRepository     
     */
    protected $mitarbeiterRepository = NULL;

    /**
     * qualifikationRepository
     * 
     * @var \Pmwebdesign\Staffm\Domain\Repository\qualifikationRepository     
     */
    protected $qualifikationRepository = NULL;

    /** Persistence Manager
     * Verwaltet Objekte aus dem blogRepository, speichern
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @inject
     */
    protected $persistenceManager;

    /**
     * 
     * @param MitarbeiterRepository $mitarbeiterRepository
     */
    public function injectMitarbeiterRepository(MitarbeiterRepository $mitarbeiterRepository)
    {
        $this->mitarbeiterRepository = $mitarbeiterRepository;
    }

    /**
     * 
     * @param \Pmwebdesign\Staffm\Domain\Repository\MitarbeiterqualifikationRepository $mitarbeiterqualifikationRepository
     */
    public function injectQualifikationRepository(QualifikationRepository $qualifikationRepository)
    {
        $this->qualifikationRepository = $qualifikationRepository;
    }

    /**
     * 
     * @param ViewInterface $view
     * @return void
     */
    public function initializeView(ViewInterface $view)
    {
        $pluginName = $this->request->getPluginName(); // PluginName ermitteln
        // Plugin = Supervisor?
        if ($pluginName == "Staffmvorg") {
            $this->view->setLayoutPathAndFilename('typo3conf/ext/staffm/Resources/Private/Layouts/LoginLayout.html');
        } elseif ($pluginName == "Staffmcustom") {
            $this->view->setLayoutPathAndFilename('typo3conf/ext/staffm/Resources/Private/Layouts/Staffmcustom.html');
        }

        $this->view->assign('menuname', 'Mitarbeiter');   
    }

    /**
     * Export employe data to Excel          
     * 
     * @return void
     */
    public function exportAction()
    {
        if ($this->request->hasArgument('searching')) {
            $search = $this->request->getArgument('searching');
        }

        // Backend User? 
//        if ($GLOBALS['BE_USER']->beUserLogin = 1) {
//            //$aktpfad = $_SERVER['SERVER_NAME']; Liest Servername aus -> intranet
//            $aktpfad = $_SERVER['DOCUMENT_ROOT']; // Funktioniert liest htdocs aus
//            //echo "<script> alert('Pfad: ".$aktpfad."'); </script>";
//            $filePath = $aktpfad . "/uploads/tx_staffm/export.xlsx";
//        } else {
//            $filePath = "uploads/tx_staffm/export.xlsx";
//        }

        $aktpfad = $_SERVER['DOCUMENT_ROOT'];
        $filePath = $aktpfad . "/uploads/tx_staffm/export.xlsx";

        $limit = 0;
        $mitarbeiters = $this->mitarbeiterRepository->findSearchForm($search, $limit);

        $_oPHPExcel = new Spreadsheet();
        $_oExcelWriter = new Xlsx($_oPHPExcel);

        // Create new Worksheet
        $myWorkSheet = new Worksheet($_oPHPExcel, 'Mitarbeiter');
        $_oPHPExcel->addSheet($myWorkSheet, 0);
        $sheetIndex = $_oPHPExcel->getIndex(
                $_oPHPExcel->getSheetByName('Worksheet')
        );
        // Delete standard worksheet
        $_oPHPExcel->removeSheetByIndex($sheetIndex);

        $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'Nachname');
        $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'Vorname');
        $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'PersNr');
        $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'AD-Name');
        $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'Titel');
        $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, 'Telefon');
        $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, 'Handy');
        $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, 'Fax');
        $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, 'Email');
        $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, 'Kostenstelle');
        $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, 'KST_Bezeichnung');
        $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 1, 'Firma');        
        for ($i = 0; $i < count($mitarbeiters); $i++) {
            $mitarbeiter = new Mitarbeiter();
            $mitarbeiter = $mitarbeiters[$i];
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $i + 2, $mitarbeiter->getLastName());
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $i + 2, $mitarbeiter->getFirstName());
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $i + 2, (string) $mitarbeiter->getPersonalnummer());
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $i + 2, $mitarbeiter->getUsername());
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $i + 2, $mitarbeiter->getTitle());
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $i + 2, $mitarbeiter->getTelephone());
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $i + 2, $mitarbeiter->getHandy());
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $i + 2, $mitarbeiter->getFax());
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $i + 2, $mitarbeiter->getEmail());            
            if ($mitarbeiter->getKostenstelle() != NULL) {
                $_oPHPExcel->getSheetByName("Mitarbeiter")->setCellValueByColumnAndRow(10, $i + 2, $mitarbeiter->getKostenstelle()->getNummer());  
                $_oPHPExcel->getSheetByName("Mitarbeiter")->setCellValueByColumnAndRow(11, $i + 2, $mitarbeiter->getKostenstelle()->getBezeichnung());                
            }
            if ($mitarbeiter->getFirma() != null) {
                $_oPHPExcel->getSheetByName("Mitarbeiter")->setCellValueByColumnAndRow(12, $i + 2, $mitarbeiter->getFirma()->getBezeichnung());
            }
        }

        // Save Excel file on intranet server
        $_oExcelWriter->save($filePath);
        unset($_oExcelWriter);
        unset($_oPHPExcel);

        // "Save as" as Download for users
        $size = filesize($filePath);        
        header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-disposition: attachment; filename=\"export.xlsx\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: $size");        
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        ob_clean(); // Very important otherwise there is a mistake at Excel file
        flush(); // Very important otherwise there is a mistake at Excel file
        readfile($filePath);

        // Delete Excel file at the server
        unlink($filePath);
    }

    /**
     * List of employees
     * 
     * @param Kostenstelle $kostenstelle
     * @return void
     */
    public function listAction(Kostenstelle $kostenstelle = NULL)
    {
        // Search exist?
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
        }

        // Clicked char?
        $char = "";
        if ($this->request->hasArgument('@widget_0')) {
            $widget = $this->request->getArgument('@widget_0');
            $char = $widget["char"];
            $search = "";
        }

        // Memorized id?
        $maid = "";
        if ($this->request->hasArgument('maid')) {
            $maid = $this->request->getArgument('maid');
        }

        // No caching flag?
        if ($this->request->hasArgument('cache')) {
            $cache = $this->request->getArgument('cache');
        }
                
        /** @var CacheService $cacheService */
        $cacheService = GeneralUtility::makeInstance(CacheService::class);    
                                        
        // No cache flag? (Example employee was deleted and the info message is shown in the list view)       
        if ($cache != "notcache" && $search == "") {
            // Cache exist?       
            if (($output = $cacheService->getCache($this->request->getControllerActionName(), $this->request->getControllerName(), $char, $maid, 0)) != NULL) {
                // Show Cache-Page
                return $output;
            }
        }

        $limit = 0;
        $mitarbeiters = $this->mitarbeiterRepository->findSearchForm($search, $limit);

        // TODO: Check if this can be deleted
//        if ($char != "" || $maid == "maid") {
//            // All clicked?
//            if ($char == '%') {
//                $search = "";               
//                $key = "all"; // TODO: check if $key is needed
//            // Char clicked?
//            } elseif ($char <> '') {
//                $search = "";   
//            // Employee id?
//            } elseif ($maid == "maid") {
//                // A employee id was send               
//                $key = "all";
//            }
//        }
        if ($this->request->hasArgument('key')) {
            $key = $this->request->getArgument('key');
            $this->view->assign('key', $key);
        }
        $this->view->assign('mitarbeiters', $mitarbeiters);
        $this->view->assign('kostenstelle', $kostenstelle);
        if ($maid != "") {
            $this->view->assign('maid', $maid);
        }

        // Search exist?
        if ($search <> "" || $cache == "notcache") {
            // Yes, no Cache is needed
            $this->view->assign('cache', '');
            $this->view->assign('search', $search);
        } else {
            // No, set Cache
            $output = $this->view->render();
            $cacheService->setCache($this->request->getControllerActionName(), $this->request->getControllerName(), $output, $char, $maid, 0);
            return $output;
        }
    }

    /**
     * List of employees for supervisors
     * 
     * @param Kostenstelle $kostenstelle
     * @return void
     */
    public function listVgsAction(Kostenstelle $kostenstelle = NULL)
    {
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
            $this->view->assign('search', $search);
        }
        if ($this->request->hasArgument('key')) {
            $key = $this->request->getArgument('key');
            $this->view->assign('key', $key);
        }

        // Logged in user              
        $aktuser = $this->objectManager->
                get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->
                findOneByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        if ($aktuser != NULL) {
            $this->view->assign('aktuser', $aktuser);
        }

        $limit = 0;
        if ($this->request->hasArgument('maid')) {
            $maid = $this->request->getArgument('maid');
            $this->view->assign('maid', $maid);
        }
        $mitarbeiters = $this->mitarbeiterRepository->findMitarbeiterVonVorgesetzten($search, $aktuser);
        $this->view->assign('mitarbeiters', $mitarbeiters);
        $this->view->assign('kostenstelle', $kostenstelle);
    }

    /**
     * List of employees who choosed in Backend
     *     
     * @return void
     */
    public function listCustomAction()
    {
        $limit = 0;

        $userService = GeneralUtility::makeInstance(UserService::class);
        $mitarbeiters = $userService->getSettingUsers($this->settings["choosedusers"]);
        $templateart = $this->settings['chooseview'];
        $this->view->assign('mitarbeiters', $mitarbeiters);
        $this->view->assign('templateart', $templateart);
    }

    /**
     * List of employees for choosing a responible for a cost center
     * 
     * @param Kostenstelle $kostenstelle
     * @return void
     */
    public function listChooseAction(Kostenstelle $kostenstelle)
    {
        $mitarbeiters = $this->mitarbeiterRepository->findAll();
        $this->view->assign('mitarbeiters', $mitarbeiters);
        $this->view->assign('kostenstelle', $kostenstelle);
    }

    /**
     * Choosing employees for a qualification
     * 
     * @param Qualifikation $qualifikation
     */
    public function listChooseQualiAction(Qualifikation $qualifikation)
    {
        $mitarbeiters = $this->mitarbeiterRepository->findSearchForm('', 0);
        $this->view->assign('mitarbeiters', $mitarbeiters);
        $this->view->assign('qualifikation', $qualifikation);
    }

    /**
     * Detail view of cost center responsible
     * 	
     * @param Kostenstelle $kostenstelle
     * @return void
     */
    public function showVeraKstAction(Kostenstelle $kostenstelle)
    {
        $mitarbeiter = $kostenstelle->getVerantwortlicher();

        // Search word?
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
            $this->view->assign('search', $search);
        }
        $this->view->assign('mitarbeiter', $mitarbeiter);
        $this->view->assign('kostenstelle', $kostenstelle);
    }

    /**
     * Detail view of employee from cost center list
     * 
     * @param Mitarbeiter $ma
     * @param Position $position
     * @param Kostenstelle $kostenstelle
     * @param Firma $firma
     * @param \Pmwebdesign\Staffm\Domain\Model\Standort $standort
     * @param Qualifikation $qualifikation
     * @return void
     */
    public function showKstAction(Mitarbeiter $ma = NULL, Position $position = NULL, Kostenstelle $kostenstelle = NULL, Firma $firma = NULL, \Pmwebdesign\Staffm\Domain\Model\Standort $standort = NULL, Qualifikation $qualifikation = NULL)
    {
        if ($ma == NULL) {
            $ma = new Mitarbeiter();
            $ma = $kostenstelle->getVerantwortlicher();
        }

        // Search word?
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
            $this->view->assign('search', $search);
        }

        $this->view->assign('mitarbeiter', $ma);
        $this->view->assign('position', $position);
        $this->view->assign('kostenstelle', $kostenstelle);
        $this->view->assign('firma', $firma);
        $this->view->assign('standort', $standort);
        $this->view->assign('qualifikation', $qualifikation);
    }

    /**
     * Show the details from an employee
     * 
     * @param integer $mitarbeiter	
     * @return void
     */
    public function showAction($mitarbeiter)
    {
        $mitarbeiter = $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->findOneByUid($mitarbeiter);
        if ($this->request->hasArgument('key')) {
            $key = $this->request->getArgument('key');
            $this->view->assign('key', $key);
        }
        // Search exist?
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
        }

        // Search status?
        if ($this->request->hasArgument('searchstatus')) {
            $searchstatus = $this->request->getArgument('searchstatus');            
            if ($searchstatus == "delete") {
                $search = "";
            }
        }

        // TODO: Test because user was logged out, after show a employee in list... | Logged in user?
        $aktuser = new Mitarbeiter();
        $aktuser = $this->objectManager->
                get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->
                findOneByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        if ($aktuser != NULL) {
            $this->view->assign('aktuser', $aktuser);
        }

        $this->view->assign('searchstatus', $searchstatus);
        $this->view->assign('search', $search);
        $this->view->assign('mitarbeiter', $mitarbeiter);
    }

    /**
     * Show the details of an employee from the custom list
     * 
     * @param integer $mitarbeiter	
     * @return void
     */
    public function showCustomAction($mitarbeiter)
    {
        $mitarbeiter = $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->findOneByUid($mitarbeiter);
        $this->view->assign('mitarbeiter', $mitarbeiter);
    }

    /**
     * Assign a responsible to a cost center
     * 
     * @param Kostenstelle $kostenstelle
     * @param Mitarbeiter $mitarbeiter
     * @return Mitarbeiter
     */
    public function chooseAction(Kostenstelle $kostenstelle, Mitarbeiter $mitarbeiter)
    {
        $this->addFlashMessage('Kostenstellenverantwortlichen "'.$mitarbeiter->getLastName().' '.$mitarbeiter->getFirstName().'" zugewiesen!', '', AbstractMessage::OK);
        $kostenstelle->setVerantwortlicher($mitarbeiter);
        $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\KostenstelleRepository')->update($kostenstelle);
        $this->persistenceManager->persistAll();

        // Delete Caches
        $cacheService = GeneralUtility::makeInstance(CacheService::class);
        $cacheService->deleteCaches($kostenstelle->getBezeichnung(), "list", "Kostenstelle", 0);
        $cacheService->deleteCaches($kostenstelle->getBezeichnung(), "show", "Kostenstelle", $kostenstelle->getUid());

        $this->redirect('edit', 'Kostenstelle', NULL, array('kostenstelle' => $kostenstelle));
    }

    /**
     * New employee form
     * 
     * @param Mitarbeiter $newMitarbeiter
     * @ignorevalidation $newMitarbeiter
     * @return void
     */
    public function newAction(Mitarbeiter $newMitarbeiter = NULL)
    {
        $this->view->assign('newMitarbeiter', $newMitarbeiter);
    }

    /**
     * Creates the new employee
     * 
     * @param Mitarbeiter $newMitarbeiter
     * @return void
     */
    public function createAction(Mitarbeiter $newMitarbeiter)
    {
        $this->addFlashMessage('Mitarbeiter angelegt!', '', AbstractMessage::OK);
        $this->mitarbeiterRepository->add($newMitarbeiter);
        $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')->persistAll();

        // Delete Caches        
        $cacheService = GeneralUtility::makeInstance(CacheService::class);
        $cacheService->deleteCaches($newMitarbeiter->getLastName(), $this->request->getControllerActionName(), $this->request->getControllerName(), 0);

        $this->redirect('edit', 'Mitarbeiter', NULL, array('mitarbeiter' => $newMitarbeiter));
    }

    /**
     * Edit form for employee
     * 
     * @param integer $mitarbeiter
     * @ignorevalidation $mitarbeiter
     * @return void
     */
    public function editAction($mitarbeiter)
    {
        $mitarbeiter = $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->findOneByUid($mitarbeiter);

        // Search exist?
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
            $this->view->assign('search', $search);
        }

        // Supervisor editing?
        if ($this->request->hasArgument('berechtigung')) {
            $berechtigung = $this->request->getArgument('berechtigung');
            $this->view->assign('berechtigung', $berechtigung);
        }

        $this->view->assign('mitarbeiter', $mitarbeiter);
    }

    /**
     * Form edit for employee
     * 
     * @param Mitarbeiter $mitarbeiter
     * @ignorevalidation $mitarbeiter
     * @return void
     */
    public function editUserAction(Mitarbeiter $mitarbeiter)
    {
        // Search exist?
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
            $this->view->assign('search', $search);
        }

        if ($this->request->hasArgument('key')) {
            $search = $this->request->getArgument('key');
            $this->view->assign('key', $search);
        }

        $this->view->assign('mitarbeiter', $mitarbeiter);
    }

    /**
     * Form edit for employee
     * 
     * @param Mitarbeiter $ma
     * @param Position $position
     * @param Kostenstelle $kostenstelle
     * @param Firma $firma
     * @param \Pmwebdesign\Staffm\Domain\Model\Standort $standort
     * @param Qualifikation $qualifikation
     * @return void
     */
    public function editKstAction(Mitarbeiter $ma, Position $position = NULL, Kostenstelle $kostenstelle = NULL, Firma $firma = NULL, \Pmwebdesign\Staffm\Domain\Model\Standort $standort = NULL, Qualifikation $qualifikation = NULL)
    {

        if ($this->request->hasArgument('kst')) {
            $kst = $this->request->getArgument('kst');
            $this->view->assign('kst', $kst);
        }

        $this->view->assign('mitarbeiter', $ma);
        $this->view->assign('position', $position);
        $this->view->assign('kostenstelle', $kostenstelle);
        $this->view->assign('firma', $firma);
        $this->view->assign('standort', $standort);
        $this->view->assign('qualifikation', $qualifikation);
    }

    /**
     * Update an employee
     * 
     * @param Mitarbeiter $mitarbeiter	 
     * @return void
     */
    public function updateAction(Mitarbeiter $mitarbeiter)
    {
        // Get assigned qualifications
        if ($this->request->hasArgument('qualifikationen')) {
            // QualificationService
            $qualificationService = GeneralUtility::makeInstance(QualificationService::class);
            $mitarbeiter->setEmployeequalifications($qualificationService->getEmployeequalificationsFromEmployee($this->request, $this->objectManager, $mitarbeiter));
            
            if (count($mitarbeiter->getEmployeequalifications()) > 0) {
                $this->addFlashMessage('Qualifikationen wurden dem Mitarbeiter "'.$mitarbeiter->getFirstName().' '.$mitarbeiter->getLastName().'" zugeordnet!', '', AbstractMessage::OK);
            } else {
                $this->addFlashMessage('Dem Mitarbeiter "'.$mitarbeiter->getFirstName().' '.$mitarbeiter->getLastName().'" wurden keine Qualifikationen zugeordnet!', '', AbstractMessage::ERROR);
            }
        } else {
            $this->addFlashMessage('Der Mitarbeiter "'.$mitarbeiter->getFirstName().' '.$mitarbeiter->getLastName().'" wurde aktualisiert!', '', AbstractMessage::OK);
        }

        $this->mitarbeiterRepository->update($mitarbeiter);

        $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')->persistAll();

        // Delete Caches        
        $cacheService = GeneralUtility::makeInstance(CacheService::class);
        $cacheService->deleteCaches($mitarbeiter->getLastName(), $this->request->getControllerActionName(), $this->request->getControllerName(), 0);

        if ($this->request->hasArgument('key')) {
            $key = $this->request->getArgument('key');
        } else {
            $key = "";
        }
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
        }

        if ($this->request->hasArgument('kst')) {
            $kst = $this->request->getArgument('kst');
        }

        if ($this->request->hasArgument('berechtigung')) {
            $berechtigung = $this->request->getArgument('berechtigung');
        }

        if ($kst == 'kst') {
            $this->redirect('editKst', 'Mitarbeiter', NULL, array('ma' => $mitarbeiter, 'kostenstelle' => $mitarbeiter->getKostenstelle()));
        } else {
            if ($key == 'auswahlUsr') {
                $this->redirect('editUser', 'Mitarbeiter', NULL, array('mitarbeiter' => $mitarbeiter, 'search' => $search, 'key' => $key));
            } else {
                $this->redirect('edit', 'Mitarbeiter', NULL, array('mitarbeiter' => $mitarbeiter, 'search' => $search, 'berechtigung' => $berechtigung));
            }
        }
    }

    /**
     * Deletes an employee
     * 
     * @param Mitarbeiter $mitarbeiter
     * @return void
     */
    public function deleteAction(Mitarbeiter $mitarbeiter)
    {
        $this->addFlashMessage('Mitarbeiter "' . $mitarbeiter->getFirstName() . ' ' . $mitarbeiter->getLastName() . '" wurde gelöscht!', '', AbstractMessage::ERROR);

        // Set employee as deleted
        $this->mitarbeiterRepository->remove($mitarbeiter);

        // Delete Caches        
        $cacheService = GeneralUtility::makeInstance(CacheService::class);
        $cacheService->deleteCaches($mitarbeiter->getLastName(), $this->request->getControllerName());

        $this->redirect('list', 'Mitarbeiter', NULL, array('cache' => 'notcache'));
    }

    /**
     * Delete qualifications of an employee
     * 
     * @param Mitarbeiter $mitarbeiter
     * @return void 
     */
    public function deleteQualiAction(Mitarbeiter $mitarbeiter)
    {
        $this->addFlashMessage('Alle Qualifikationen vom Mitarbeiter "' . $mitarbeiter->getFirstName() . ' ' . $mitarbeiter->getLastName() . '" wurden gelöscht!', '', AbstractMessage::ERROR);

        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
        }

        if ($this->request->hasArgument('berechtigung')) {
            $berechtigung = $this->request->getArgument('berechtigung');
        }
        $employeequalifications = new ObjectStorage();
        $mitarbeiter->setEmployeequalifications($employeequalifications);
        $this->mitarbeiterRepository->update($mitarbeiter);
        $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')->persistAll();
        $this->redirect('edit', 'Mitarbeiter', NULL, array('mitarbeiter' => $mitarbeiter, 'search' => $search, 'berechtigung' => $berechtigung));
    }

    /**
     * Set TypeConverter option for image upload
     */
    public function initializeCreateAction()
    {
        $this->setTypeConverterConfigurationForImageUpload('newMitarbeiter');
    }

    /**
     * Set TypeConverter option for image upload
     */
    public function initializeUpdateAction()
    {
        $this->setTypeConverterConfigurationForImageUpload('mitarbeiter');
    }

    /**
     * Configuration for Image Upload
     */
    protected function setTypeConverterConfigurationForImageUpload($argumentName)
    {
        $uploadConfiguration = [
            UploadedFileReferenceConverter::CONFIGURATION_ALLOWED_FILE_EXTENSIONS => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
            UploadedFileReferenceConverter::CONFIGURATION_UPLOAD_FOLDER => '1:/user_upload/profilbilder/',
        ];
        /** @var PropertyMappingConfiguration $newMitarbeiterConfiguration */
        $newMitarbeiterConfiguration = $this->arguments[$argumentName]->getPropertyMappingConfiguration();

        // Convert all images
        for ($i = 0; $i < 99; $i++) {
            $newMitarbeiterConfiguration->forProperty('image.' . $i)
                    ->setTypeConverterOptions(
                            'Pmwebdesign\\Staffm\\Property\\TypeConverter\\UploadedFileReferenceConverter', $uploadConfiguration
            );
        }
    }

    /**
     * Initialize action 
     * 
     * @return void 
     */
    public function initializeAction()
    {
        parent::initializeAction();

        /* Caching Framework */
        $this->cache = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager')->getCache('staffm_mycache');
    }
}
