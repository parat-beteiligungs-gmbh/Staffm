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

use Pmwebdesign\Staffm\Property\TypeConverter\UploadedFileReferenceConverter;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * FirmaController
 */
class FirmaController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     *
     * @var \TYPO3\CMS\Core\Cache\CacheManager
     */
    protected $cache;

    /**
     * firmaRepository
     * 
     * @var \Pmwebdesign\Staffm\Domain\Repository\FirmaRepository	
     */
    protected $firmaRepository = NULL;

    /**
     * mitarbeiterRepository
     * 
     * @var \Pmwebdesign\Staffm\Domain\Repository\MitarbeiterRepository	 
     */
    protected $mitarbeiterRepository = NULL;

    /**
     * 
     * @param \Pmwebdesign\Staffm\Domain\Repository\FirmaRepository $firmaRepository
     */
    public function injectFirmaRepository(\Pmwebdesign\Staffm\Domain\Repository\FirmaRepository $firmaRepository)
    {
        $this->firmaRepository = $firmaRepository;
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
        $this->view->assign('menuname', 'Firma');
    }
    
    /**
     * Set TypeConverter option for image upload
     */
    public function initializeCreateAction()
    {
        $this->setTypeConverterConfigurationForImageUpload('newFirma');
    }
    
    /**
     * Set TypeConverter option for image upload
     */
    public function initializeUpdateAction()
    {
        $this->setTypeConverterConfigurationForImageUpload('firma');
        $this->setTypeConverterConfigurationForFileUpload('firma');
    }
    
    /**
     * Image Configuration
     */
    protected function setTypeConverterConfigurationForImageUpload($argumentName)
    {
        $uploadConfiguration = [
            UploadedFileReferenceConverter::CONFIGURATION_ALLOWED_FILE_EXTENSIONS => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
            UploadedFileReferenceConverter::CONFIGURATION_UPLOAD_FOLDER => '1:/user_upload/firmen/',
        ];
        /** @var PropertyMappingConfiguration $newFirmaConfiguration */
        $newFirmaConfiguration = $this->arguments[$argumentName]->getPropertyMappingConfiguration();
        
        for($i= 0; $i < 99; $i++) {
            $newFirmaConfiguration->forProperty('images.'.$i)
                ->setTypeConverterOptions(
                    'Pmwebdesign\\Staffm\\Property\\TypeConverter\\UploadedFileReferenceConverter',
                    $uploadConfiguration
                );
        }
    }
    
    /**
     * File Configuration
     */
    protected function setTypeConverterConfigurationForFileUpload($argumentName)
    {
        $uploadConfiguration = [
            UploadedFileReferenceConverter::CONFIGURATION_ALLOWED_FILE_EXTENSIONS => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
            UploadedFileReferenceConverter::CONFIGURATION_UPLOAD_FOLDER => '1:/user_upload/firmen/',
        ];
        /** @var PropertyMappingConfiguration $newMitarbeiterConfiguration */
        $newFirmaConfiguration = $this->arguments[$argumentName]->getPropertyMappingConfiguration();
        
        for($i= 0; $i < 99; $i++) {
            $newFirmaConfiguration->forProperty('files.'.$i)
                ->setTypeConverterOptions(
                    'Pmwebdesign\\Staffm\\Property\\TypeConverter\\UploadedFileReferenceConverter',
                    $uploadConfiguration
                );
        }
    }

    /**
     * action export 
     * Daten in Excel exportieren           
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Firma $firma
     * @return void
     */
    public function exportAction(\Pmwebdesign\Staffm\Domain\Model\Firma $firma = NULL)
    {
        if ($this->request->hasArgument('searching')) {
            $search = $this->request->getArgument('searching');
        }

        $aktpfad = $_SERVER['DOCUMENT_ROOT'];
        $filePath = $aktpfad . "/uploads/tx_staffm/export.xlsx";

        $limit = 0;

        $_oPHPExcel = new Spreadsheet();
        $_oExcelWriter = new Xlsx($_oPHPExcel);

        // Prüfen ob Qualifikationen, oder Mitarbeiter ausgegeben werden sollen
        if ($firma == NULL) {
            // Qualifikationen sollen ausgegeben werden
            $firmen = $this->firmaRepository->findSearchForm($search, $limit);

            // Create new Worksheet
            $myWorkSheet = new Worksheet($_oPHPExcel, 'Positionen');
            $_oPHPExcel->addSheet($myWorkSheet, 0);
            $sheetIndex = $_oPHPExcel->getIndex(
                    $_oPHPExcel->getSheetByName('Worksheet')
            );
            // Delete standard worksheet
            $_oPHPExcel->removeSheetByIndex($sheetIndex);

            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'Firmen');

            $i = 0;
            foreach ($firmen as $firma) {
                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $i + 2, $firma->getBezeichnung());
                $i++;
            }
        } else {
            // Mitarbeiter sollen ausgegeben werden
            $mitarbeiters = $firma->getMitarbeiters();

            // Create new Worksheet
            $myWorkSheet = new Worksheet($_oPHPExcel, 'Mitarbeiter');
            $_oPHPExcel->addSheet($myWorkSheet, 0);
            $sheetIndex = $_oPHPExcel->getIndex(
                    $_oPHPExcel->getSheetByName('Worksheet')
            );
            // Delete standard worksheet
            $_oPHPExcel->removeSheetByIndex($sheetIndex);

            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "Mitarbeiterliste für die Firma " . $firma->getBezeichnung());
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
            //$_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 4, 'Qualifikation');
            //for ($i = 0; $i < count($mitarbeiters); $i++) { // funktioniert nicht!
            $i = 0;
            foreach ($mitarbeiters as $mitarbeiter) {
                //$mitarbeiter = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter();
                //$mitarbeiter = $mitarbeiters[$i];    

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
                //$_oPHPExcel->getSheetByName("Mitarbeiter")->setCellValueByColumnAndRow(12, $i + 5, $qualifikation->getBezeichnung());
                $i++;
            }
        }

        // Excel-Datei auf Server sichern		
        $_oExcelWriter->save($filePath);
        unset($_oExcelWriter);
        unset($_oPHPExcel);

        // Speichern unter verfügbar machen für User
        $size = filesize($filePath);
        //header("Content-type: application/octet-stream"); 
        header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-disposition: attachment; filename=\"export.xlsx\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: $size");
        //header("Pragma: no-cache"); 
        //header("Expires: 0");
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        ob_clean(); // Sehr wichtig sonst Fehler bei Excel-Datei
        flush(); // Sehr wichtig sonst Fehler bei Excel-Datei
        readfile($filePath);

        // Excel-Datei auf Server löschen
        unlink($filePath);
    }

    /**
     * action list
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
     * @return void
     */
    public function listAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter = NULL)
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
            //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($widget);
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
        
        // No cache flag? (Example company was deleted and the info message is shown in the list view)
        if ($cache != "notcache") {
            /* Caching Framework */  
            $speak = $GLOBALS['TSFE']->sys_language_uid; // Language Index
            $cachename = $speak."listFirmIdentifier";
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
        $firmas = $this->firmaRepository->findSearchForm($search, $limit);
        // Überprüfen ob Argument gesetzt wurde
        if ($this->request->hasArgument('key')) {
            $key = $this->request->getArgument('key');
            $this->view->assign('key', $key);
        }

        // Ursprüngliche Suche?
        if ($this->request->hasArgument('standardsearch')) {
            $standardsearch = $this->request->getArgument('standardsearch');
            $this->view->assign('standardsearch', $standardsearch);
        }

        $this->view->assign('firmas', $firmas);
        $this->view->assign('mitarbeiter', $mitarbeiter);
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
     * action show
     *   
     * @param integer $firma 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
     * @return void
     */
    public function showAction($firma = 0, \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter = NULL)
    {
        $speak = $GLOBALS['TSFE']->sys_language_uid; // Language Index
        
        // Employee?        
        if ($mitarbeiter != NULL) {     
            $firma = $mitarbeiter->getFirma(); 
            $key = $this->request->getArgument('key');
            $this->view->assign('key', $key);        
            $namecache = $speak."showMitaFirmaIdentifier".$mitarbeiter->getUid();  
        } else {
            $namecache = $speak."showFirmIdentifier".$firma;  
            $firma = $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\FirmaRepository')->findOneByUid($firma); 
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
        
        /* Caching Framework */
        // Cache of logged in user with admin authorization available?
        if ((($output = $this->cache->get($namecache."Adm")) !== false) && $admin == TRUE) {   
            // Yes, return Cache
            return $output;
        }
        
        // Cache for normal user available?
        if ((($output = $this->cache->get($namecache)) !== false) && $admin == FALSE) {     
            // Yes, return Cache
            return $output;
        }

        // Ursprüngliche Suche?
        if ($this->request->hasArgument('standardsearch')) {
            $standardsearch = $this->request->getArgument('standardsearch');
            $this->view->assign('standardsearch', $standardsearch);
        }     
        
        $this->view->assign('mitarbeiter', $mitarbeiter);
        $this->view->assign('firma', $firma);
        // Set Cache
        $output = $this->view->render(); // Render view
        if($admin == TRUE) {
            $this->cache->set($namecache."Adm", $output);
        } else {
            $this->cache->set($namecache, $output);
        }        
        return $output;        
    }

    /**
     * action choose
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
     * @param \Pmwebdesign\Staffm\Domain\Model\Firma $firma
     * @return \Pmwebdesign\Staffm\Domain\Model\Firma
     */
    public function chooseAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter, \Pmwebdesign\Staffm\Domain\Model\Firma $firma)
    {
        $mitarbeiter->setFirma($firma);
        $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->update($mitarbeiter);

        // Ursprüngliche Suche?
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
        }

        $this->redirect('edit', 'Mitarbeiter', NULL, array('mitarbeiter' => $mitarbeiter, 'search' => $search));
    }

    /**
     * action deleteFirma
     * Löscht die Firma eines Mitarbeiters
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter	
     */
    public function deleteFirmaAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter)
    {
        $mitarbeiter->setFirma(NULL);
        $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->update($mitarbeiter);
        $this->redirect('edit', 'Mitarbeiter', NULL, array('mitarbeiter' => $mitarbeiter));
    }

    /**
     * action new
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Firma $newFirma
     * @ignorevalidation $newFirma
     * @return void
     */
    public function newAction(\Pmwebdesign\Staffm\Domain\Model\Firma $newFirma = NULL)
    {
        $this->view->assign('newFirma', $newFirma);
    }

    /**
     * action create
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Firma $newFirma
     * @return void
     */
    public function createAction(\Pmwebdesign\Staffm\Domain\Model\Firma $newFirma)
    {
        $this->addFlashMessage('Firma angelegt!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->firmaRepository->add($newFirma);
        
        // Delete Caches
        $char = strtoupper(substr($newFirma->getBezeichnung(), 0, 1));
        $this->cache->remove("0listFirmIdentifier");
        $this->cache->remove("1listFirmIdentifier");
        $this->cache->remove("0listFirmIdentifierAll");
        $this->cache->remove("1listFirmIdentifierAll");
        $this->cache->remove("0listFirmIdentifier".$char);
        $this->cache->remove("1listFirmIdentifier".$char);
        $this->cache->remove("0listFirmIdentifierAdm");
        $this->cache->remove("1listFirmIdentifierAdm");
        $this->cache->remove("0listFirmIdentifierAllAdm");
        $this->cache->remove("1listFirmIdentifierAllAdm");
        $this->cache->remove("0listFirmIdentifier".$char."Adm");
        $this->cache->remove("1listFirmIdentifier".$char."Adm");
        $this->redirect('list', 'Firma', NULL, array('cache' => 'notcache'));
    }

    /**
     * action edit
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Firma $firma
     * @ignorevalidation $firma
     * @return void
     */
    public function editAction(\Pmwebdesign\Staffm\Domain\Model\Firma $firma)
    {
        $this->view->assign('firma', $firma);
    }

    /**
     * action update
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Firma $firma
     * @return void
     */
    public function updateAction(\Pmwebdesign\Staffm\Domain\Model\Firma $firma)
    {
        $this->addFlashMessage('Die Firma "' . $firma->getBezeichnung() . '" wurde aktualisiert!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->firmaRepository->update($firma);
        
        // Delete Caches
        $char = strtoupper(substr($firma->getBezeichnung(), 0, 1));
        $this->cache->remove("0listFirmIdentifier");
        $this->cache->remove("1listFirmIdentifier");
        $this->cache->remove("0listFirmIdentifierAll");
        $this->cache->remove("1listFirmIdentifierAll");
        $this->cache->remove("0listFirmIdentifier".$char);
        $this->cache->remove("1listFirmIdentifier".$char);
        $this->cache->remove("0listFirmIdentifierAdm");
        $this->cache->remove("1listFirmIdentifierAdm");
        $this->cache->remove("0listFirmIdentifierAllAdm");
        $this->cache->remove("1listFirmIdentifierAllAdm");
        $this->cache->remove("0listFirmIdentifier".$char."Adm");
        $this->cache->remove("1listFirmIdentifier".$char."Adm");
        $this->cache->remove("0showFirmIdentifier".$firma->getUid());
        $this->cache->remove("1showFirmIdentifier".$firma->getUid());
        $this->cache->remove("0showFirmIdentifier".$firma->getUid()."Adm");
        $this->cache->remove("1showFirmIdentifier".$firma->getUid()."Adm");
                
        $this->redirect('edit', 'Firma', NULL, array('firma' => $firma));
    }

    /**
     * Delete Company
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Firma $firma
     * @return void
     */
    public function deleteAction(\Pmwebdesign\Staffm\Domain\Model\Firma $firma)
    {
        // Firma löschen von den Mitarbeitern
        foreach ($this->mitarbeiterRepository->findFirmaMitarbeiter($firma) as $m) {
            $m->setFirma(NULL);
            $this->mitarbeiterRepository->update($m);
        }
        
        $this->addFlashMessage('Die Firma "' . $firma->getBezeichnung() . '" wurde gelöscht!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->firmaRepository->remove($firma);
        
        // Delete Caches
        $char = strtoupper(substr($firma->getBezeichnung(), 0, 1));
        $this->cache->remove("0listFirmIdentifier");
        $this->cache->remove("1listFirmIdentifier");
        $this->cache->remove("0listFirmIdentifierAll");
        $this->cache->remove("1listFirmIdentifierAll");
        $this->cache->remove("0listFirmIdentifier".$char);
        $this->cache->remove("1listFirmIdentifier".$char);
        $this->cache->remove("0listFirmIdentifierAdm");
        $this->cache->remove("1listFirmIdentifierAdm");
        $this->cache->remove("0listFirmIdentifierAllAdm");
        $this->cache->remove("1listFirmIdentifierAllAdm");
        $this->cache->remove("0listFirmIdentifier".$char."Adm");
        $this->cache->remove("1listFirmIdentifier".$char."Adm");
        $this->cache->remove("0showFirmIdentifier".$firma->getUid());
        $this->cache->remove("1showFirmIdentifier".$firma->getUid());
        $this->cache->remove("0showFirmIdentifier".$firma->getUid()."Adm");
        $this->cache->remove("1showFirmIdentifier".$firma->getUid()."Adm");
        
        $this->redirect('list', 'Firma', NULL, array('cache' => 'notcache'));
    }

}
