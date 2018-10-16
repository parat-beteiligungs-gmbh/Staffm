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

namespace Pmwebdesign\Staffm\Controller;

use PHPOffice\PhpSpreadsheet\Spreadsheet; // Class must exist in composer.json under ps4
use PHPOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PHPOffice\PhpSpreadsheet\Writer\Xlsx; 
use Pmwebdesign\Staffm\Property\TypeConverter\UploadedFileReferenceConverter;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Employee Controller
 * 
 * @author Markus Puffer (m.puffer@pm-webdesign.eu)
 */
class MitarbeiterController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
    
    /**
     * Caching Framework
     *
     * @var \TYPO3\CMS\Core\Cache\CacheManager     
     */
    protected $cache;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter>
     */
    protected $objects;

    /**
     * mitarbeiterRepository
     * 
     * @var \Pmwebdesign\Staffm\Domain\Repository\MitarbeiterRepository     
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
     * @param \Pmwebdesign\Staffm\Domain\Repository\MitarbeiterRepository $mitarbeiterRepository
     */
    public function injectMitarbeiterRepository(\Pmwebdesign\Staffm\Domain\Repository\MitarbeiterRepository $mitarbeiterRepository) {
        $this->mitarbeiterRepository = $mitarbeiterRepository;
    }
    
    /**
     * 
     * @param \Pmwebdesign\Staffm\Domain\Repository\MitarbeiterqualifikationRepository $mitarbeiterqualifikationRepository
     */
    public function injectQualifikationRepository(\Pmwebdesign\Staffm\Domain\Repository\QualifikationRepository $qualifikationRepository) {
        $this->qualifikationRepository = $qualifikationRepository;
    }

    /**
     * TODO: test change protected to public
     * @param \TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view
     * @return void
     */
    public function initializeView(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view) {        
        $pluginName = $this->request->getPluginName(); // PluginName ermitteln
        
        // Plugin = Vorgesetzter?
        if ($pluginName == "Staffmvorg") {            
            $this->view->setLayoutPathAndFilename('typo3conf/ext/staffm/Resources/Private/Layouts/LoginLayout.html');
        } elseif ($pluginName == "Staffmcustom") {
            $this->view->setLayoutPathAndFilename('typo3conf/ext/staffm/Resources/Private/Layouts/Staffmcustom.html');
        }    
        
        $this->view->assign('menuname', 'Mitarbeiter');
        /*if($this->view->assign('list', $this->actionMethodName)) {
            echo $this->actionMethodName;
        }*/
    }

    /**
     * action export 
     * Export employe data to Excel          
     * @return void
     */
    public function exportAction() {
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
        //$_oPHPExcel->getSheetByName("Test")->setCellValue('A1', 'Titel');
        for ($i = 0; $i < count($mitarbeiters); $i++) {
            $mitarbeiter = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter();
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
            if ($mitarbeiter->getKostenstelle() != null) {
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
        //header("Content-type: application/octet-stream"); 
        header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-disposition: attachment; filename=\"export.xlsx\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: $size");
        /* header("Pragma: no-cache"); 
          header("Expires: 0"); */
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        ob_clean(); // important otherwise there is a mistake at Excel file
        flush(); // very important otherwise there is a mistake at Excel file
        readfile($filePath);

        // Delete Excel file at the server
        unlink($filePath);
    }

    /**
     * action list
     * @param \Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle
     * @return void
     */
    public function listAction(\Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle = NULL) {  
        
        // Search exist?
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
        } 
        
        // Clicked char?
        if ($this->request->hasArgument('@widget_0')) {
            $widget = $this->request->getArgument('@widget_0');            
            $char = $widget["char"];
        }  
        
        $maid = "";
        if ($this->request->hasArgument('maid')) {
            $maid = $this->request->getArgument('maid');   
        }
        
        // No caching?
        if ($this->request->hasArgument('cache')) {
            $cache = $this->request->getArgument('cache');            
        } 
        
        // No cache flag? (Example employee was deleted and the info message is shown in the list view)
        if ($cache != "notcache") {
            
            /* Caching Framework */     
            $speak = $GLOBALS['TSFE']->sys_language_uid;
            $cachename = $speak."listMitaIdentifier";
            $keyforcache = array('list', 'normal');
            // Char or employee id?
            if ($char != "" || $maid == "maid") {
                // All clicked?
                if($char == '%') {
                    $search = "";
                    $char = "All";
                    $key = "all";      
                // Char clicked?
                } elseif ($char <> '') {
                    $search = "";
                    // No, a other char is clicked
                    $char = $char;                                
                // Employee id?
                } elseif ($maid == "maid") {
                    // A employee id was send
                    $char = "All";
                    $key = "all";                
                }

                $cachename = $cachename.$char;
                $keyforcache = array('list', 'buchstabe', $char);
            }

            // Groups of User
            $groups = $this->settings["admingroups"];        
            if($groups == NULL) {
                $admin = FALSE;
            } else {
                $userService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\UserService::class);
                // User is admin?
                $admin = $userService->isAdmin($groups);        
            }
            
            // Cache of logged in user with admin authorization available?
            if ((($output = $this->cache->get($cachename."Adm")) !== false) && $search == "" && $admin == TRUE) {   
                // Yes, return Cache
                return $output;
            }

            // Cache for normal user available?        
            if ((($output = $this->cache->get($cachename)) !== false) && $search == "" && $admin == FALSE) {   
                // Yes, return Cache
                return $output;
            }
        }

        $limit = 0;
        $mitarbeiters = $this->mitarbeiterRepository->findSearchForm($search, $limit);
        
        if ($this->request->hasArgument('key')) {
            $key = $this->request->getArgument('key');

            $this->view->assign('key', $key);            
        }            
       // Search field content delete?
        if ($this->request->hasArgument('searchstatus')) {            
            $searchstatus = $this->request->getArgument('searchstatus');  
        }
        
        $this->view->assign('mitarbeiters', $mitarbeiters);
        $this->view->assign('kostenstelle', $kostenstelle);
        $this->view->assign('search', $search);        
        if ($maid != "") {
            $this->view->assign('maid', $maid);
        }

        // Search exist?
        if ($search <> "" || $cache == "notcache") {
            // Yes, no Cache is needed
            $this->view->assign('cache', '');
        } else {            
            // No, set Cache
            $ouput = $this->view->render();
            if($admin == TRUE) {
                $this->cache->set($cachename."Adm", $ouput, $keyforcache);
            } else {
                $this->cache->set($cachename, $ouput, $keyforcache);
            }
            return $ouput;
        }        
    }
    
    /**
     * action list
     * @param \Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle
     * @return void
     */
    public function listVgsAction(\Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle = NULL) {        
        // $GLOBALS['TSFE']->reqCHash();
        
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
     * action list custom    
     * @return void
     */
    public function listCustomAction() {         
        $limit = 0;    
        
        $userService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\UserService::class);
        $mitarbeiters = $userService->getSettingUsers($this->settings["choosedusers"]);
        $templateart = $this->settings['chooseview'];
        $this->view->assign('mitarbeiters', $mitarbeiters);
        $this->view->assign('templateart', $templateart);
    }
    
    /**
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiterqualifikation $a
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiterqualifikation $b
     * @return \Pmwebdesign\Staffm\Domain\Model\Mitarbeiterqualifikation
     */
    function cmp(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiterqualifikation $a, 
            \Pmwebdesign\Staffm\Domain\Model\Mitarbeiterqualifikation $b) {
        if ($a->getQualifikation()->getBezeichnung() == $b->getQualifikation()->getBezeichnung()) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }

    /**
     * action listChoose
     * Liste of employees for choosing a responible for a cost center
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle
     * @return void
     */
    public function listChooseAction(\Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle) {
        $mitarbeiters = $this->mitarbeiterRepository->findAll();
        $this->view->assign('mitarbeiters', $mitarbeiters);
        $this->view->assign('kostenstelle', $kostenstelle);
    }
    
    /**
     * action listChooseQuali
     * Choosing employees for a qualification
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualifikation
     */
    public function listChooseQualiAction(\Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualifikation) {     
        $mitarbeiters = $this->mitarbeiterRepository->findSearchForm('', 0);
        $this->view->assign('mitarbeiters', $mitarbeiters);
        $this->view->assign('qualifikation', $qualifikation);
    }

    /**
     * action show
     * Detail view of cost center responsible
     * 	
     * @param \Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle
     * @return void
     */
    public function showVeraKstAction(\Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle) {
        $mitarbeiter = $kostenstelle->getVerantwortlicher();
                
        // Suchwort?
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
            $this->view->assign('search', $search);
        }        
        $this->view->assign('mitarbeiter', $mitarbeiter);       
        $this->view->assign('kostenstelle', $kostenstelle);       
    }

    /**
     * action show
     * Detail view of employee from cost center list
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $ma
     * @param \Pmwebdesign\Staffm\Domain\Model\Position $position
     * @param \Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle
     * @param \Pmwebdesign\Staffm\Domain\Model\Firma $firma
     * @param \Pmwebdesign\Staffm\Domain\Model\Standort $standort
     * @param \Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualifikation
     * @return void
     */
    public function showKstAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $ma = NULL, \Pmwebdesign\Staffm\Domain\Model\Position $position = NULL, \Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle = NULL, \Pmwebdesign\Staffm\Domain\Model\Firma $firma = NULL, \Pmwebdesign\Staffm\Domain\Model\Standort $standort = NULL, \Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualifikation = NULL) {
        if ($ma == NULL) {
            $ma = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter();
            $ma = $kostenstelle->getVerantwortlicher();
        }
        // Mitarbeiterqualifikationen        
//        $mq = $this->mitarbeiterqualifikationRepository->findSearchForm($ma->getUid());
//        $ma->setMitarbeiterQualifikationen($mq);
        
        // Suchwort?
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
            $this->view->assign('search', $search);
        }
        
//        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($ma);
        $this->view->assign('mitarbeiter', $ma);  
        $this->view->assign('position', $position);
        $this->view->assign('kostenstelle', $kostenstelle);
        $this->view->assign('firma', $firma);
        $this->view->assign('standort', $standort);
        $this->view->assign('qualifikation', $qualifikation);
    }

    /**
     * action show
     * Show the details from an employee
     * 
     * @param integer $mitarbeiter	
     * @return void
     */
    public function showAction($mitarbeiter) {  
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
            // TODO: Suchstatus delete wird nicht übertragen
            if ($searchstatus == "delete") {
                $search = "";
            }
        }   
        
        // TODO: Test because user was logged out, after show a employee in list... | Logged in user?
        $aktuser = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter();
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
     * action showCustom
     * Show the details of an employee from the custom list
     * 
     * @param integer $mitarbeiter	
     * @return void
     */
    public function showCustomAction($mitarbeiter) {     
        $mitarbeiter = $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->findOneByUid($mitarbeiter);
        $this->view->assign('mitarbeiter', $mitarbeiter);
    }

    /**
     * action choose
     * Assign a responsible to a cost center
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
     * @return \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter
     */
    public function chooseAction(\Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle, \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter) {
        $kostenstelle->setVerantwortlicher($mitarbeiter);
        $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\KostenstelleRepository')->update($kostenstelle);   
        $this->persistenceManager->persistAll();
        
        // Delete Caches
        $char = strtoupper(substr($kostenstelle->getBezeichnung(), 0, 1));
        $this->cache->remove("listKstIdentifier");
        $this->cache->remove("listKstIdentifierAll");
        $this->cache->remove("listKstIdentifier".$char);
        $this->cache->remove("listKstIdentifierAdm");
        $this->cache->remove("listKstIdentifierAllAdm");
        $this->cache->remove("listKstIdentifier".$char."Adm");
        $this->cache->remove("showKstIdentifier".$kostenstelle->getUid());
        
        $this->redirect('edit', 'Kostenstelle', NULL, array('kostenstelle' => $kostenstelle));
    }

    /**
     * action new
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $newMitarbeiter
     * @ignorevalidation $newMitarbeiter
     * @return void
     */
    public function newAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $newMitarbeiter = NULL) {
        $this->view->assign('newMitarbeiter', $newMitarbeiter);
    }

    /**
     * action create
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $newMitarbeiter
     * @return void
     */
    public function createAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $newMitarbeiter) {
        $this->addFlashMessage('Mitarbeiter angelegt!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        // TODO: Set Pid, because in settings (setup.txt) the right Pid is not used
        //$newMitarbeiter->setPid(28);
        $this->mitarbeiterRepository->add($newMitarbeiter);
        //$this->redirect('list');
        $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')->persistAll();
        
        // Delete Caches
        $char = strtoupper(substr($newMitarbeiter->getLastName(), 0, 1));
        $this->cache->remove("0listMitaIdentifier");
        $this->cache->remove("1listMitaIdentifier");
        $this->cache->remove("0listMitaIdentifierAll");
        $this->cache->remove("1listMitaIdentifierAll");
        $this->cache->remove("0listMitaIdentifier".$char);
        $this->cache->remove("1listMitaIdentifier".$char);
        $this->cache->remove("0listMitaIdentifierAdm");
        $this->cache->remove("1listMitaIdentifierAdm");
        $this->cache->remove("0listMitaIdentifierAllAdm");
        $this->cache->remove("1listMitaIdentifierAllAdm");
        $this->cache->remove("0listMitaIdentifier".$char."Adm");   
        $this->cache->remove("1listMitaIdentifier".$char."Adm");   
        
        $this->redirect('edit', 'Mitarbeiter', NULL, array('mitarbeiter' => $newMitarbeiter));
    }

    /**
     * action edit
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
     * @ignorevalidation $mitarbeiter
     * @return void
     */
    public function editAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter) {
        // Mitarbeiterqualifikationen        
        //$mq = $this->mitarbeiterqualifikationRepository->findSearchForm($mitarbeiter->getUid());
        //$mitarbeiter->setMitarbeiterQualifikationen($mq);
        
        // Suchwort?
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
            $this->view->assign('search', $search);
        }        
        
        // Vorgesetzter Bearbeitung?
        if ($this->request->hasArgument('berechtigung')) {
            $berechtigung = $this->request->getArgument('berechtigung');
            $this->view->assign('berechtigung', $berechtigung);
        }
        
        $this->view->assign('mitarbeiter', $mitarbeiter);        
    }
    
    /**
     * action edit
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
     * @ignorevalidation $mitarbeiter
     * @return void
     */
    public function editUserAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter) {
        // Mitarbeiterqualifikationen        
        //$mq = $this->mitarbeiterqualifikationRepository->findSearchForm($mitarbeiter->getUid());
        //$mitarbeiter->setMitarbeiterQualifikationen($mq);
        
        // Suchwort?
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
     * action edit
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $ma
     * @param \Pmwebdesign\Staffm\Domain\Model\Position $position
     * @param \Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle
     * @param \Pmwebdesign\Staffm\Domain\Model\Firma $firma
     * @param \Pmwebdesign\Staffm\Domain\Model\Standort $standort
     * @param \Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualifikation
     * @return void
     */
    public function editKstAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $ma, 
            \Pmwebdesign\Staffm\Domain\Model\Position $position = NULL, 
            \Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle = NULL, 
            \Pmwebdesign\Staffm\Domain\Model\Firma $firma = NULL, 
            \Pmwebdesign\Staffm\Domain\Model\Standort $standort = NULL, 
            \Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualifikation = NULL) {
                
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
     * action update
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter	 
     * @return void
     */
    public function updateAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter) { 
        $this->addFlashMessage('Mitarbeiter/in "'.$mitarbeiter->getFirstName().' '.$mitarbeiter->getLastName().'" wurde aktualisiert!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        
        if ($this->request->hasArgument('qualifikationen')) {              
            $employeequalifications = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
            
            // Read checkboxes into array
            $qua = $this->request->getArgument('qualifikationen');
            
            // Read status
            if($this->request->hasArgument('qualificationsstatus')) {
                $qualificationsstatus = $this->request->getArgument('qualificationsstatus');                
            }
            // Read notes
            if($this->request->hasArgument('qualificationsnotes')) {
                $qualificationsnotes = $this->request->getArgument('qualificationsnotes');
            }
            
            // Set qualifications to array items
            foreach ($qua as $q) {      
                $employeequalification = new \Pmwebdesign\Staffm\Domain\Model\Employeequalification();
                $qualification = $this->objectManager->get(
                        'Pmwebdesign\\Staffm\\Domain\\Repository\\QualifikationRepository'
                )->findOneByUid($q);    
                $employeequalification->setEmployee($mitarbeiter);
                $employeequalification->setQualification($qualification);
                $status = $qualificationsstatus[$qualification->getUid()];
                $note = $qualificationsnotes[$qualification->getUid()];
                if($status != null) {
                    $employeequalification->setStatus($status);
                }
                if($note != null) {
                    $employeequalification->setNote($note);
                }
                $employeequalifications->attach($employeequalification);				
            }                     
            $mitarbeiter->setEmployeequalifications($employeequalifications);   
        }
        
        $this->mitarbeiterRepository->update($mitarbeiter);      
        
        $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')->persistAll();
        
        // Delete Caches
        // If char is example 'Á'
        $char = strtoupper(substr($mitarbeiter->getLastName(), 0, 2));
        if ($char == 'Á') {              
            $char = 'A';            
        } else if ($char == 'Ó') {
            $char = 'O';
        } else {
            $char = strtoupper(substr($mitarbeiter->getLastName(), 0, 1));
        }
        $this->cache->remove("0listMitaIdentifier");
        $this->cache->remove("1listMitaIdentifier");
        $this->cache->remove("0listMitaIdentifierAll");
        $this->cache->remove("1listMitaIdentifierAll");
        $this->cache->remove("0listMitaIdentifier".$char);
        $this->cache->remove("1listMitaIdentifier".$char);
        $this->cache->remove("0listMitaIdentifierAdm");
        $this->cache->remove("1listMitaIdentifierAdm");
        $this->cache->remove("0listMitaIdentifierAllAdm");
        $this->cache->remove("1listMitaIdentifierAllAdm");
        $this->cache->remove("0listMitaIdentifier".$char."Adm");    
        $this->cache->remove("1listMitaIdentifier".$char."Adm");    
        
        if ($this->request->hasArgument('key')) {
            $key = $this->request->getArgument('key');                        
        } else {
            $key = "";
        }
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');                        
        }
        
        if ($this->request->hasArgument('kst')) {
            $kst= $this->request->getArgument('kst');                        
        }
        
        if($this->request->hasArgument('berechtigung')) {
            $berechtigung = $this->request->getArgument('berechtigung');
        }
        
        if ($kst == 'kst') {
            $this->redirect('editKst', 'Mitarbeiter', NULL, array('ma' => $mitarbeiter, 'kostenstelle' => $mitarbeiter->getKostenstelle()));
        } else {
            if($key == 'auswahlUsr') {
                $this->redirect('editUser', 'Mitarbeiter', NULL, array('mitarbeiter' => $mitarbeiter, 'search' => $search, 'key' => $key));            
            } else {
                $this->redirect('edit', 'Mitarbeiter', NULL, array('mitarbeiter' => $mitarbeiter, 'search' => $search, 'berechtigung' => $berechtigung)); 
            }
        }
    }

    /**
     * action delete
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
     * @return void
     */
    public function deleteZurückgestelltAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter) {
        $mitarbeiter->setDeleted(1);
        $this->addFlashMessage('Mitarbeiter gelöscht!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->mitarbeiterRepository->update($mitarbeiter);
        $this->redirect('list');
    }

    /**
     * action delete
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
     * @return void
     */
    public function deleteAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter) {
        // Flashmessage
        $this->addFlashMessage('Mitarbeiter "'.$mitarbeiter->getFirstName().' '.$mitarbeiter->getLastName().'" wurde gelöscht!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        
        // Set employee as deleted
        $this->mitarbeiterRepository->remove($mitarbeiter);
        
        // Delete Caches       
        // If char is example 'Á'
        $char = strtoupper(substr($mitarbeiter->getLastName(), 0, 2));
        if ($char == 'Á') {              
            $char = 'A';            
        } else if ($char == 'Ó') {
            $char = 'O';
        } else {
            $char = strtoupper(substr($mitarbeiter->getLastName(), 0, 1));
        }
        $this->cache->remove("0listMitaIdentifier");
        $this->cache->remove("1listMitaIdentifier");
        $this->cache->remove("0listMitaIdentifierAll");
        $this->cache->remove("1listMitaIdentifierAll");
        $this->cache->remove("0listMitaIdentifier".$char);
        $this->cache->remove("1listMitaIdentifier".$char);
        $this->cache->remove("0listMitaIdentifierAdm");
        $this->cache->remove("1listMitaIdentifierAdm");
        $this->cache->remove("0listMitaIdentifierAllAdm");
        $this->cache->remove("1listMitaIdentifierAllAdm");
        $this->cache->remove("0listMitaIdentifier".$char."Adm");    
        $this->cache->remove("1listMitaIdentifier".$char."Adm");    
        
        $this->redirect('list', 'Mitarbeiter', NULL, array('cache' => 'notcache'));
    }
    
    /**
     * action deleteQuali
     * Delete qualifications of an employee
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
     * @return void 
     */
    public function deleteQualiAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter)
    {
        $this->addFlashMessage('Alle Qualifikationen vom Mitarbeiter "'.$mitarbeiter->getFirstName().' '.$mitarbeiter->getLastName().'" wurden gelöscht!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');                        
        }
        
        if($this->request->hasArgument('berechtigung')) {
            $berechtigung = $this->request->getArgument('berechtigung');
        }
        $employeequalifications = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $mitarbeiter->setEmployeequalifications($employeequalifications);
        //$mitarbeiter->setQualifikationen($qualifikationen);
        $this->mitarbeiterRepository->update($mitarbeiter);
        $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')->persistAll();
        $this->redirect('edit', 'Mitarbeiter', NULL, array('mitarbeiter' => $mitarbeiter, 'search' => $search, 'berechtigung' => $berechtigung)); 
    }

    /**
     * Add a MitarbeiterQualifikation
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter 	
     * @return void
     */
    public function addMitarbeiterQualifikationen(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter) {

        $quali = $this->request->getArgument('qualifikationcb');
        $mitarbeiterQualifikation = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiterqualifikation();
        $mitarbeiterQualifikation->setMitarbeiter($mitarbeiter);
        $mitarbeiterQualifikation->setQualifikation($this->objectManager->get(
                        'Pmwebdesign\\Staffm\\Domain\\Repository\\QualifikationRepository'
                )->findOneByUid(1));
        /* $arrMitarbeiterQualifikation = array();
          $arrMitarbeiterQualifikation =& new $mitarbeiterQualifikation; */
        $arrMitarbeiterQualifikation = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $arrMitarbeiterQualifikation->attach($mitarbeiterQualifikation);
        $qualis = $arrMitarbeiterQualifikation;
        $this->objectManager->get(
                'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager'
        )->persistAll();
        
        $this->mitarbeiterqualifikationRepository->add($mitarbeiterQualifikation);
        $mitarbeiter->setMitarbeiterQualifikationen($arrMitarbeiterQualifikation);
        $this->forward('edit', 'Mitarbeiter', NULL, array('mitarbeiter' => $mitarbeiter, 'qualis' => $qualis));

        $this->mitarbeiterQualifikationen->attach($mitarbeiterQualifikation);
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
//        $newMitarbeiterConfiguration->forProperty('image')
//            ->setTypeConverterOptions(
//                'Pmwebdesign\\Staffm\\Property\\TypeConverter\\UploadedFileReferenceConverter',
//                $uploadConfiguration
//            );
        for($i= 0; $i < 99; $i++) {
            $newMitarbeiterConfiguration->forProperty('image.'.$i)
                ->setTypeConverterOptions(
                    'Pmwebdesign\\Staffm\\Property\\TypeConverter\\UploadedFileReferenceConverter',
                    $uploadConfiguration
                );
        }
    }

    /**
     * initialize action - converts the image type
     * 
     * @return void 
     */
    public function initializeAction() {            
        parent::initializeAction();
        
        /* Caching Framework */
        $this->cache = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager')->getCache('staffm_mycache');
        
        // Caching -> Test with Class Caching
//        $this->cache->initializeCache(\TYPO3\CMS\Core\Utility\GeneralUtility::camelCaseToLowerCaseUnderscored($this->extensionName));
//        parent::initializeAction();
        
        // If argument "mitarbeiter"
//        if ($this->arguments->hasArgument('mitarbeiter')) {         
//            //$this->arguments->getArgument('mitarbeiter')->getPropertyMappingConfiguration()->allowProperties('image');
//            $this->arguments->getArgument('mitarbeiter')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('image', 'TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage<\\TYPO3\\CMS\Extbase\\Domain\\Model\\FileReference>'); // Image in Array umwandeln
//            // $this->arguments->getArgument('blog')->getPropertyMappingConfiguration()->allowCreationForSubProperty('image'); // Test vom inet
//        } 
//        if ($this->arguments->hasArgument('newMitarbeiter')) {
//            $this->arguments->getArgument('newMitarbeiter')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('image', 'TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>'); // Image in Array umwandeln
//        }
    }
}
