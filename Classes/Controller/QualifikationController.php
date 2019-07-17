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

use PHPOffice\PhpSpreadsheet\Spreadsheet;
use PHPOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PHPOffice\PhpSpreadsheet\Writer\Xlsx;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Qualification Controller
 * 
 * @author Markus Puffer (m.puffer@pm-webdesign.eu)
 */
class QualifikationController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     *
     * @var \TYPO3\CMS\Core\Cache\CacheManager 
     */
    protected $cache;

    /**
     * Qualification Repository
     * 
     * @var \Pmwebdesign\Staffm\Domain\Repository\QualifikationRepository	
     */
    protected $qualifikationRepository = NULL;

    /**
     * Qualilog Repository
     * 
     * @var \Pmwebdesign\Staffm\Domain\Repository\QualilogRepository	
     */
    protected $qualilogRepository = NULL;

    /**
     * Employee Repository
     *
     * @var \Pmwebdesign\Staffm\Domain\Repository\MitarbeiterRepository	
     */
    protected $mitarbeiterRepository = NULL;

    /**
     * 
     * @param \Pmwebdesign\Staffm\Domain\Repository\QualifikationRepository $qualifikationRepository
     */
    public function injectQualifikationRepository(\Pmwebdesign\Staffm\Domain\Repository\QualifikationRepository $qualifikationRepository)
    {
        $this->qualifikationRepository = $qualifikationRepository;
    }

    /**
     * 
     * @param \Pmwebdesign\Staffm\Domain\Repository\QualilogRepository $qualilogRepository
     */
    public function injectQualilogRepository(\Pmwebdesign\Staffm\Domain\Repository\QualilogRepository $qualilogRepository)
    {
        $this->qualilogRepository = $qualilogRepository;
    }

    /**
     * 
     * @param \Pmwebdesign\Staffm\Domain\Repository\MitarbeiterRepository $mitarbeiterRepository
     */
    public function injectMitarbeiterRepository(\Pmwebdesign\Staffm\Domain\Repository\MitarbeiterRepository $mitarbeiterRepository)
    {
        $this->mitarbeiterRepository = $mitarbeiterRepository;
    }
    
    /**
     * Initialize Action
     * Call before other actions
     */
    protected function initializeAction()
    {
        parent::initializeAction();

        /* Caching Framework */
        $this->cache = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager')->getCache('staffm_mycache');
    }

    /**
     * @param \TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view
     * @return void
     */
    protected function initializeView(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view)
    {
        $pluginName = $this->request->getPluginName(); // PluginName ermitteln
        // Plugin = Vorgesetzter?
        if ($pluginName == "Staffmvorg") {
            $this->view->setLayoutPathAndFilename('typo3conf/ext/staffm/Resources/Private/Layouts/LoginLayout.html');
        }
        $this->view->assign('menuname', 'Qualifikation');
    }

    /**
     * Export data to Excel          
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualifikation
     * @return void
     */
    public function exportAction(\Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualifikation = NULL)
    {
        if ($this->request->hasArgument('searching')) {
            $search = $this->request->getArgument('searching');
        }

        $aktpfad = $_SERVER['DOCUMENT_ROOT'];
        $filePath = $aktpfad . "/uploads/tx_staffm/export.xlsx";

        $limit = 0;

        $_oPHPExcel = new Spreadsheet();
        $_oExcelWriter = new Xlsx($_oPHPExcel);

        // Show qualifications?
        if ($qualifikation == NULL) {
            $qualifikationen = $this->qualifikationRepository->findSearchForm($search, $limit);

            // Create new Worksheet
            $myWorkSheet = new Worksheet($_oPHPExcel, 'Qualifikationen');
            $_oPHPExcel->addSheet($myWorkSheet, 0);
            $sheetIndex = $_oPHPExcel->getIndex(
                    $_oPHPExcel->getSheetByName('Worksheet')
            );
            // Delete standard worksheet
            $_oPHPExcel->removeSheetByIndex($sheetIndex);

            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'Qualifikation');
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'Beschreibung');
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'Verantwortlicher');
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'Anzahl Mitarbeiter');
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'Zugeordnete Mitarbeiter');
            
            for ($i = 0; $i < count($qualifikationen); $i++) {
                $qualifikation = new \Pmwebdesign\Staffm\Domain\Model\Qualifikation();
                $qualifikation = $qualifikationen[$i];
                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $i + 2, $qualifikation->getBezeichnung());
                echo $qualifikation->getBezeichnung() . " ";
                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $i + 2, $qualifikation->getBeschreibung());
                // Last editor
                $qualil = new \Pmwebdesign\Staffm\Domain\Model\Qualilog();
                $qualilogs = $qualifikation->getQualilogs();

                foreach ($qualilogs as $value) {
                    $qualil = $value;
                    break;
                }
                // Deleted editor?
                if ($qualil->getBearbeiter() != NULL) {
                    $bearbeiter = $qualil->getBearbeiter()->getLastName() . " " . $qualil->getBearbeiter()->getFirstName();
                } else {
                    $bearbeiter = "";
                }

                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $i + 2, $bearbeiter);
                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $i + 2, count($qualifikation->getEmployeequalifications()));
                $stringMitarbeiter = "";
                $employeequalifications = $qualifikation->getEmployeequalifications();
                if ($employeequalifications != NULL) {
                    foreach ($employeequalifications as $employeequalification) {
                        $employee = $employeequalification->getEmployee();
                        if ($employee != NULL) {
                            $mit = "" . $employee->getLastName() . " " . $employee->getFirstName() . ",";
                            $stringMitarbeiter = $stringMitarbeiter . $mit;
                        }
                    }
                }
                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $i + 2, $stringMitarbeiter);
                echo "<br />";
            }
        } else {
            // Show employees
            $employeequalifications = $qualifikation->getEmployeequalifications();

            // Create new Worksheet
            $myWorkSheet = new Worksheet($_oPHPExcel, 'Qualifikationen');
            $_oPHPExcel->addSheet($myWorkSheet, 0);
            $sheetIndex = $_oPHPExcel->getIndex(
                    $_oPHPExcel->getSheetByName('Worksheet')
            );
            // Delete standard worksheet
            $_oPHPExcel->removeSheetByIndex($sheetIndex);

            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "Mitarbeiterliste für die Qualifikation " . $qualifikation->getBezeichnung());
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 2, "(" . $qualifikation->getBeschreibung() . ")");
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Nachname');
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'Vorname');
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'PersNr');
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'AD-Name');
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Titel');
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Telefon');
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 4, 'Handy');
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 4, 'Fax');
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 4, 'Email');
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 4, 'Kostenstelle');
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 4, 'KST_Bezeichnung');
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 4, 'Firma');
            
            $i = 0;
            foreach ($employeequalifications as $employeequalification) {
                $mitarbeiter = $employeequalification->getEmployee();
                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $i + 5, $mitarbeiter->getLastName());
                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $i + 5, $mitarbeiter->getFirstName());
                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $i + 5, (string) $mitarbeiter->getPersonalnummer());
                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $i + 5, $mitarbeiter->getUsername());
                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $i + 5, $mitarbeiter->getTitle());
                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $i + 5, $mitarbeiter->getTelephone());
                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $i + 5, $mitarbeiter->getHandy());
                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $i + 5, $mitarbeiter->getFax());
                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $i + 5, $mitarbeiter->getEmail());
                if ($mitarbeiter->getKostenstelle() != null) {
                    $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $i + 5, $mitarbeiter->getKostenstelle()->getNummer());
                    $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $i + 5, $mitarbeiter->getKostenstelle()->getBezeichnung());
                }
                if ($mitarbeiter->getFirma() != null) {
                    $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $i + 5, $mitarbeiter->getFirma()->getBezeichnung());
                }
                $i++;
            }
        }

        // Save Excel file at server
        $_oExcelWriter->save($filePath);
        unset($_oExcelWriter);
        unset($_oPHPExcel);

        // "Save at" for users
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

        // Delete Excel file at server
        unlink($filePath);
    }
    
    /**
     * Export data to Excel          
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualifikation
     * @return void
     */
    public function exportQualisEmployeesAction(\Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualifikation = NULL)
    {
        // Get Category Field
        if($this->request->hasArgument('categoryfieldExcel')) {
            $categoryfield = $this->request->getArgument('categoryfieldExcel');
        }
        
        // Get User
        /* @var $userService \Pmwebdesign\Staffm\Domain\Service\UserService */
        $userService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\UserService::class);
        $aktUser = $userService->getLoggedInUser();
               
        // Employees of cost center responsible
        $mitarbeiter = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\MitarbeiterRepository::class)->findMitarbeiterVonVorgesetzten(Null, $aktUser);
        
        // Category Field not empty and not catAll?
        if($categoryfield != "" && $categoryfield != "catAll") {
            /* @var $category \Pmwebdesign\Staffm\Domain\Model\Category */
            $category = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\CategoryRepository::class)->findOneByName($categoryfield);
            // Category?
            if($category) {
                $qualifications = $category->getQualifications();
            } else {
                $qualifications = $this->qualifikationRepository->findSearchForm("", 0);                
            }            
        } else {
            // Show all qualifications
            $qualifications = $this->qualifikationRepository->findSearchForm("", 0);
        }                

        $aktpfad = $_SERVER['DOCUMENT_ROOT'];
        $filePath = $aktpfad . "/uploads/tx_staffm/export.xlsx";

        $limit = 0;

        $_oPHPExcel = new Spreadsheet();
        $_oExcelWriter = new Xlsx($_oPHPExcel);

        // Create new Worksheet
        $myWorkSheet = new Worksheet($_oPHPExcel, 'Qualifikationen');
        $_oPHPExcel->addSheet($myWorkSheet, 0);
        $sheetIndex = $_oPHPExcel->getIndex(
                $_oPHPExcel->getSheetByName('Worksheet')
        );
        // Delete standard worksheet
        $_oPHPExcel->removeSheetByIndex($sheetIndex);

        $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'Mitarbeiter / Qualifikationen');
        $i = 2;
        // List qualifications in header
        foreach ($qualifications as $qualification) {
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, 1, $qualification->getBezeichnung());
            $i++;
        }
        // List employees in the left column
        $r = 2;
        foreach ($mitarbeiter as $employee) {
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $r, $employee->getLastName()." ".$employee->getFirstName());
            $i = 2;
            foreach ($qualifications as $qualification) {                
                foreach ($employee->getEmployeequalifications() as $employeequalification) {
                    if($qualification == $employeequalification->getQualification()) {
                        $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $r, $employeequalification->getStatus());
                    }                    
                }
                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, 1, $qualification->getBezeichnung());
                $i++;
            }
            $r++;
        }

        // Save Excel file at server
        $_oExcelWriter->save($filePath);
        unset($_oExcelWriter);
        unset($_oPHPExcel);

        // "Save at" for users
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

        // Delete Excel file at server
        unlink($filePath);
    }

    /**
     * List of qualifications
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
     * @param \Pmwebdesign\Staffm\Domain\Model\Category $category
     * @return void
     */
    public function listAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter = NULL,
            \Pmwebdesign\Staffm\Domain\Model\Category $category = NULL)
    {
        // Search word?
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
        } else {
            $search = NULL;
        }        
        // Clicked char?
        if ($this->request->hasArgument('@widget_0')) {
            $widget = $this->request->getArgument('@widget_0');
            $char = $widget["char"];
        }
        // Id?
        $maid = "";
        if ($this->request->hasArgument('maid')) {
            $maid = $this->request->getArgument('maid');
        }        
        // No caching?
        if ($this->request->hasArgument('cache')) {
            $cache = $this->request->getArgument('cache');            
        } 
        
        if ($this->request->hasArgument('art')) {
            $art = $this->request->getArgument('art');
            $this->view->assign('art', $art);
        }     
                
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);
        
        // No cache flag? (Example qualification was deleted and the info message is shown in the list view)
        if ($cache != "notcache" && $search == "") {            
            // Cache exist?       
            if (($output = $cacheService->getCache($this->request->getControllerActionName(), $this->request->getControllerName(), $char, $maid, 0)) != NULL) {
                // Show Cache-Page
                return $output;
            }
        }

        $limit = 0;
        $qualifikations = $this->qualifikationRepository->findSearchForm($search, $limit);
                
        if ($this->request->hasArgument('key')) {
            $key = $this->request->getArgument('key');        
            $this->view->assign('key', $key);
            // Assign qualification for users?
            if($key == 'auswahl' || $key == 'auswahlUsr') {
                // Yes, show categories
                $categories = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\CategoryRepository::class)->findAll();
                $this->view->assign('categories', $categories);
            }
        }       
        
        if ($this->request->hasArgument('userKey')) {
            $userKey = $this->request->getArgument('userKey');        
            $this->view->assign('userKey', $userKey);
        }
        
         if ($this->request->hasArgument('kst')) {
            $kst = $this->request->getArgument('kst');        
            $this->view->assign('kst', $kst);
        }

        if ($this->request->hasArgument('berechtigung')) {
            $berechtigung = $this->request->getArgument('berechtigung');
            $this->view->assign('berechtigung', $berechtigung);
        }

        $this->view->assign('qualifikations', $qualifikations);
        $this->view->assign('mitarbeiter', $mitarbeiter);
        $this->view->assign('category', $category);
        $this->view->assign('search', $search);
        if ($maid != "") {
            $this->view->assign('maid', $maid);
        }
        
        // Get logged in User    
        /* @var $aktuser \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter */
        $aktuser = $this->objectManager->
                get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->
                findOneByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        if($aktuser != NULL) {
            $categoryfield = "";
            if($this->request->hasArgument('categoryfield')) {
                $categoryfield = $this->request->getArgument('categoryfield');              
            } else {            
                foreach ($aktuser->getCategories() as $category) {
                    $categoryfield = $category->getName();
                    break;
                }
            }
            $this->view->assign('categoryfield', $categoryfield);
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
     * List view of employees for supervisors (employees from his cost centers)
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
     * @return void
     */
    public function listVgsAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter = NULL)
    {
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
        } else {
            $search = NULL;
        }

        $limit = 0;
        $qualifikations = $this->qualifikationRepository->findSearchForm($search, $limit);

        if ($this->request->hasArgument('key')) {
            $key = $this->request->getArgument('key');
            $this->view->assign('key', $key);
        }

        // Get logged in User    
        /* @var $aktuser \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter */
        $aktuser = $this->objectManager->
                get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->
                findOneByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        // transfer user to view
        if ($aktuser != NULL) {
            $this->view->assign('aktuser', $aktuser);

            // Employees of cost center responsible
            $mitarbeiter = $this->mitarbeiterRepository->findMitarbeiterVonVorgesetzten(Null, $aktuser);

            $this->view->assign('mitarbeiters', $mitarbeiter);
        } else {
            $this->view->assign('mitarbeiter', $mitarbeiter);
        }

        // Back to id
        if ($this->request->hasArgument('maid')) {
            $maid = $this->request->getArgument('maid');
            $this->view->assign('maid', $maid);
        }
       
        $categories = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\CategoryRepository::class)->findAll();
                        
        $categoryfield = "";
        if($this->request->hasArgument('categoryfield')) {
            $categoryfield = $this->request->getArgument('categoryfield');              
        } else {
            foreach ($aktuser->getCategories() as $category) {
                $categoryfield = $category->getName();
                break;
            }
        }
        if($this->request->hasArgument("url")) {
            $url = $this->request->getArgument("url");
            $this->redirectToUri($url);
        } else {
            $this->view->assign('categoryfield', $categoryfield);
            $this->view->assign('categories', $categories);        
            $this->view->assign('qualifikations', $qualifikations);        
            $this->view->assign('search', $search);
        }
    }

    /**
     * Single View of the qualification
     * 
     * @param integer $qualifikation	
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
     * @return void
     */
    public function showAction($qualifikation, \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter = NULL)
    {
        if ($this->request->hasArgument('key')) {
            $key = $this->request->getArgument('key');
            $this->view->assign('key', $key);
            $this->view->assign('mitarbeiter', $mitarbeiter);
        } else {
            $this->view->assign('key', 'fromQualification');
        }
        
        // Get logged in user        
        $aktuser = $this->objectManager->
                get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->
                findOneByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        
        if ($aktuser != NULL) {
            $this->view->assign('aktuser', $aktuser);
            
            // Cost Centers from the responsible logged in user
            $kostenstellen = $this->objectManager->
                    get('Pmwebdesign\\Staffm\\Domain\\Repository\\KostenstelleRepository')->
                    findByVerantwortlicher($GLOBALS['TSFE']->fe_user->user['uid']);
            $this->view->assign('kostenstellen', $kostenstellen);
        }

        // Search word?
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
            $this->view->assign('search', $search);
        }

        // Previous search?
        if ($this->request->hasArgument('standardsearch')) {
            $standardsearch = $this->request->getArgument('standardsearch');
            $this->view->assign('standardsearch', $standardsearch);
        }
        
        if ($this->request->hasArgument('userKey')) {
            $this->view->assign('userKey', $this->request->getArgument('userKey'));
        }
        
        $qualifikation = $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\QualifikationRepository')->findOneByUid($qualifikation);
        $this->view->assign('qualifikation', $qualifikation);
    }

    /**
     * New form for qualification
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Qualifikation $newQualifikation
     * @ignorevalidation $newQualifikation
     * @return void
     */
    public function newAction(\Pmwebdesign\Staffm\Domain\Model\Qualifikation $newQualifikation = NULL)
    {
        $this->view->assign('newQualifikation', $newQualifikation);
    }

    /**
     * Create a qualification
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Qualifikation $newQualifikation
     * @return void
     */
    public function createAction(\Pmwebdesign\Staffm\Domain\Model\Qualifikation $newQualifikation)
    {
        // Create Log
        $qualil = new \Pmwebdesign\Staffm\Domain\Model\Qualilog();
        $qualil->setQualifikation($newQualifikation);
        $qualil->setBezeichnung($newQualifikation->getBezeichnung());
        $qualil->setBeschreibung($newQualifikation->getBeschreibung());

        // Get Editor	
        $userid = $GLOBALS['TSFE']->fe_user->user['uid'];
        // Frontend-User?
        if ($userid != null) {
            // Yes, frontend user
            $qualil->setBearbeiter($this->objectManager->get(
                            'Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository'
                    )->findOneByUid(
                            $GLOBALS['TSFE']->fe_user->user['uid']
                    )
            );
        } else {
            // No, backend user
            $qualil->setBearbeiter($this->objectManager->get(
                            'Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository'
                    )->findOneByUsername(
                            $GLOBALS['BE_USER']->user['username']
                    )
            );
        }
        $qualil->setStatus("angelegt");
        
        $this->qualilogRepository->add($qualil);

        $this->addFlashMessage('Qualifikation angelegt!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->qualifikationRepository->add($newQualifikation);
        
        // Delete Caches
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);
        $cacheService->deleteCaches($newQualifikation->getBezeichnung(), "list", $this->request->getControllerName(), 0); 
        
        $this->redirect('list', 'Qualifikation', NULL, array('cache' => 'notcache'));
    }

    /**
     * Edit form for qualification
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualifikation
     * @ignorevalidation $qualifikation
     * @return void
     */
    public function editAction(\Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualifikation)
    {
        $this->view->assign('qualifikation', $qualifikation);
    }

    /**
     * Update a qualification
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualifikation
     * @return void
     */
    public function updateAction(\Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualifikation)
    {
        // Create log
        $qualil = new \Pmwebdesign\Staffm\Domain\Model\Qualilog();
        $qualil->setQualifikation($qualifikation);
        $qualil->setBezeichnung($qualifikation->getBezeichnung());
        $qualil->setBeschreibung($qualifikation->getBeschreibung());
        
        // Get actually user
        $userService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\UserService::class);
        $qualil->setBearbeiter($userService->getLoggedInUser());
        
        $qualil->setStatus("aktualisiert");        
        $this->qualilogRepository->add($qualil);
        $this->objectManager->get(
                'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager'
        )->persistAll();

        // Get assigned employees
        if ($this->request->hasArgument('mitarbeiters')) {     
            $qualificationService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\QualificationService::class);
            $employeequalifications = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
            $qualifikation->setEmployeequalifications($qualificationService->getEmployeequalificationsFromQualification($this->request, $this->objectManager, $qualifikation));
        } 
        
        // Get assigned categories
        if ($this->request->hasArgument('categories')) {     
            /* @var $categoryService \Pmwebdesign\Staffm\Domain\Service\CategoryService */
            $categoryService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CategoryService::class);
            $categories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
            $qualifikation->setCategories($categoryService->getCategories($this->request, $this->objectManager));
        }
        
        $this->qualifikationRepository->update($qualifikation);
        
        $this->addFlashMessage('Die Qualifikation "' . $qualifikation->getBezeichnung() . '" wurde aktualisiert!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        
        // Delete Caches
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);
        $cacheService->deleteCaches($qualifikation->getBezeichnung(), "list", $this->request->getControllerName(), 0); 
        $cacheService->deleteCaches($qualifikation->getBezeichnung(), "show", $this->request->getControllerName(), $qualifikation->getUid());
        
        $this->redirect('edit', 'Qualifikation', NULL, array('qualifikation' => $qualifikation, 'search' => $search, 'berechtigung' => $berechtigung));
    }

    /**
     * Delete a qualification
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualifikation
     * @return void
     */
    public function deleteAction(\Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualifikation)
    {
        // Create log
        $qualil = new \Pmwebdesign\Staffm\Domain\Model\Qualilog();
        $qualil->setQualifikation($qualifikation);
        $qualil->setBezeichnung($qualifikation->getBezeichnung());
        $qualil->setBeschreibung($qualifikation->getBeschreibung());

        // Get Editor
        $userid = $GLOBALS['TSFE']->fe_user->user['uid'];
        // Frontend-User?
        if ($userid != null) {
            // Yes, Frontend-User
            $qualil->setBearbeiter($this->objectManager->get(
                            'Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository'
                    )->findOneByUid(
                            $GLOBALS['TSFE']->fe_user->user['uid']
                    )
            );
        } else {
            // No, Backend-User
            $qualil->setBearbeiter($this->objectManager->get(
                            'Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository'
                    )->findOneByUsername(
                            $GLOBALS['BE_USER']->user['username']
                    )
            );
        }
        $qualil->setStatus("gelöscht");
        $this->objectManager->get(
                'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager'
        )->persistAll();
        $this->qualilogRepository->add($qualil);

        // Delete qualifications of assigned employees
        $qualifikation->deleteMitarbeiters();
        $this->qualifikationRepository->update($qualifikation);

        $this->objectManager->get(
                'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager'
        )->persistAll();
        
        $this->addFlashMessage('Qualifikation gelöscht!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->qualifikationRepository->remove($qualifikation);
        
        // Delete Caches
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);
        $cacheService->deleteCaches($qualifikation->getBezeichnung(), "list", $this->request->getControllerName(), 0); 
        $cacheService->deleteCaches($qualifikation->getBezeichnung(), "show", $this->request->getControllerName(), $qualifikation->getUid());
        
        $this->redirect('list', 'Qualifikation', NULL, array('cache' => 'notcache'));
    }
    
    /**
     * Multi selection of qualifications and employees
     * Only for cost center responsibles
     * 	
     * @return \Pmwebdesign\Staffm\Domain\Repository\Mitarbeiterqualifikation
     */
    public function chooselistAction()
    {        
        // Set qualifications with status to employees
        /* @var $qualificationService \Pmwebdesign\Staffm\Domain\Service\QualificationService */
        $qualificationService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\QualificationService::class);
        $qualificationService->setEmployeequalificationsFromQualifications($this->request, $this->objectManager);
        
        $categoryfield = "";
        if ($this->request->hasArgument('categoryfield')) {
            $categoryfield = $this->request->getArgument('categoryfield');              
        }       
        $this->addFlashMessage('Die Qualifikationen wurden gesichert!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        
        $url = $this->request->getArgument("url");
        
        $this->redirect("listVgs", "Qualifikation", null, ['categoryfield' => $categoryfield, 'url' => $url]);
    }
}
