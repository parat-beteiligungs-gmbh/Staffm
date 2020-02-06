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
 * Company Controller
 */
class FirmaController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     *
     * @var \TYPO3\CMS\Core\Cache\CacheManager
     */
    protected $cache;

    /**
     * Company repository
     * 
     * @var \Pmwebdesign\Staffm\Domain\Repository\FirmaRepository	
     */
    protected $firmaRepository = NULL;

    /**
     * Employee repository
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
     * Export data to excel         
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

        // Show companies?
        if ($firma == NULL) {
            // Yes, show companies
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
            // Show employees
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
                $i++;
            }
        }

        // Save excel file on server
        $_oExcelWriter->save($filePath);
        unset($_oExcelWriter);
        unset($_oPHPExcel);

        // Save as for user
        $size = filesize($filePath);
        header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-disposition: attachment; filename=\"export.xlsx\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: $size");
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        ob_clean(); // Very important otherwise a error occurs in the Excel-File
        flush(); // Very important otherwise a error occurs in the Excel-File
        readfile($filePath);

        // Delete excel file on server
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
        
        // No caching?
        if ($this->request->hasArgument('cache')) {
            $cache = $this->request->getArgument('cache');            
        } 
        
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);
                
        // No cache flag? (Example employee was deleted and the info message is shown in the list view)       
        if ($cache != "notcache" && $search == "") { 
            // Cache exist?       
            if(($output = $cacheService->getCache($this->request->getControllerActionName(), $this->request->getControllerName(), $char, $maid, 0)) != NULL) {
                // Show Cache-Page
                return $output;
            }
        }

        $limit = 0;
        $firmas = $this->firmaRepository->findSearchForm($search, $limit);
        
        if ($this->request->hasArgument('key')) {
            $key = $this->request->getArgument('key');
            $this->view->assign('key', $key);
        }

        // Previous search?
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
            $this->view->assign('search', $search);   
        } else {            
            // No, set Cache
            $ouput = $this->view->render();
            if($admin == TRUE) {
                $this->cache->set($cachename."Adm", $ouput, $keyforcache);
            } else {
                // No, set Cache
                $output = $this->view->render();
                $cacheService->setCache($this->request->getControllerActionName(), $this->request->getControllerName(), $output, $char, $maid, 0);
                return $output;
            }
            return $ouput;
        }
    }

    /**
     * action show
     *   
     * @param integer $firma 
     * @param integer $mitarbeiter
     * @return void
     */
    public function showAction($firma = 0, $mitarbeiter = 0)
    {        
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);
        // Employee?        
        if ($mitarbeiter != 0) {    
            // Cache exist?       
            if(($output = $cacheService->getCache($this->request->getControllerActionName(), "Mitarbeiter".$this->request->getControllerName(), "", "", $mitarbeiter)) != NULL) {                
                // Show Cache-Page
                return $output; 
            }    
            $mitarbeiter = $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->findByUid($mitarbeiter);
            $firma = $mitarbeiter->getFirma(); 
            $key = $this->request->getArgument('key');
            $this->view->assign('key', $key);        
        } else {
            if(($output = $cacheService->getCache($this->request->getControllerActionName(), $this->request->getControllerName(), "", "", $firma)) != NULL) {
                // Show Cache-Page
                return $output;
            }
            $firma = $this->firmaRepository->findByUid($firma);     
        }
        // Previous search?
        if ($this->request->hasArgument('standardsearch')) {
            $standardsearch = $this->request->getArgument('standardsearch');
            $this->view->assign('standardsearch', $standardsearch);
        }  
        $this->view->assign('mitarbeiter', $mitarbeiter);
        $this->view->assign('firma', $firma);
        
        if ($this->request->hasArgument('userKey')) {
            $this->view->assign('userKey', $this->request->getArgument('userKey'));
        }
        
        // Set Cache
        $output = $this->view->render();
        if ($mitarbeiter != NULL && $mitarbeiter != 0) {
            $cacheService->setCache($this->request->getControllerActionName(), "Mitarbeiter".$this->request->getControllerName(), $output, "", "", $mitarbeiter->getUid());
        } else {
            $cacheService->setCache($this->request->getControllerActionName(), $this->request->getControllerName(), $output, "", "", $firma->getUid());
        }
        return $output;      
    }

    /**
     * Select a company to employee
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
     * @param \Pmwebdesign\Staffm\Domain\Model\Firma $firma
     * @return \Pmwebdesign\Staffm\Domain\Model\Firma
     */
    public function chooseAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter, \Pmwebdesign\Staffm\Domain\Model\Firma $firma)
    {
        $mitarbeiter->setFirma($firma);
        $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->update($mitarbeiter);
        
        // Delete Cache from cost center
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);        
        $cacheService->deleteCaches($firma->getBezeichnung(), "show", $this->request->getControllerName(), $firma->getUid());

        // Previous search?
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
        }

        $this->redirect('edit', 'Mitarbeiter', NULL, array('mitarbeiter' => $mitarbeiter, 'search' => $search));
    }

    /**
     * Deletes the company of an employee
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter	
     */
    public function deleteFirmaAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter)
    {
        $firma = $mitarbeiter->getFirma();
        $mitarbeiter->setFirma(NULL);
        $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->update($mitarbeiter);
        
        // Delete Cache from cost center
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);        
        $cacheService->deleteCaches($firma->getBezeichnung(), "show", $this->request->getControllerName(), $firma->getUid());
        
        $this->redirect('edit', 'Mitarbeiter', NULL, array('mitarbeiter' => $mitarbeiter));
    }

    /**
     * New company form
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
     * Create a new company
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Firma $newFirma
     * @return void
     */
    public function createAction(\Pmwebdesign\Staffm\Domain\Model\Firma $newFirma)
    {
        $this->addFlashMessage('Firma angelegt!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->firmaRepository->add($newFirma);
       
        // Delete Caches
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);
        $cacheService->deleteCaches($newFirma->getBezeichnung(), "list", $this->request->getControllerName(), 0);
       
        $this->redirect('list', 'Firma', NULL, array('cache' => 'notcache'));
    }

    /**
     * Edit the company
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
     * Update a company
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Firma $firma
     * @return void
     */
    public function updateAction(\Pmwebdesign\Staffm\Domain\Model\Firma $firma)
    {
        $this->addFlashMessage('Die Firma "' . $firma->getBezeichnung() . '" wurde aktualisiert!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->firmaRepository->update($firma);
                
        // Delete Caches
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);
        $cacheService->deleteCaches($firma->getBezeichnung(), "list", $this->request->getControllerName(), 0);
        $cacheService->deleteCaches($firma->getBezeichnung(), "show", $this->request->getControllerName(), $firma->getUid());
                
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
        // Delete this company from all assigned employees
        foreach ($this->mitarbeiterRepository->findFirmaMitarbeiter($firma) as $m) {
            $m->setFirma(NULL);
            $this->mitarbeiterRepository->update($m);
        }
        
        $this->addFlashMessage('Die Firma "' . $firma->getBezeichnung() . '" wurde gelöscht!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->firmaRepository->remove($firma);
        
        // Delete Caches
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);
        $cacheService->deleteCaches($firma->getBezeichnung(), "list", $this->request->getControllerName(), 0);
        $cacheService->deleteCaches($firma->getBezeichnung(), "show", $this->request->getControllerName(), $firma->getUid());
        
        $this->redirect('list', 'Firma', NULL, array('cache' => 'notcache'));
    }
}
