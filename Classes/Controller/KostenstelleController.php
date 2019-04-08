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
 * Cost Center Controller
 */
class KostenstelleController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     *
     * @var \TYPO3\CMS\Core\Cache\CacheManager
     */
    protected $cache;

    /**
     * Cost Center Repository
     * 
     * @var \Pmwebdesign\Staffm\Domain\Repository\KostenstelleRepository    
     */
    protected $kostenstelleRepository = NULL;

    /**
     * 
     * @param \Pmwebdesign\Staffm\Domain\Repository\KostenstelleRepository $kostenstelleRepository
     */
    public function injectKostenstelleRepository(\Pmwebdesign\Staffm\Domain\Repository\KostenstelleRepository $kostenstelleRepository)
    {
        $this->kostenstelleRepository = $kostenstelleRepository;
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
        $this->view->assign('menuname', 'Kostenstelle');
    }
    
    /**
     * Set TypeConverter option for image upload
     */
    public function initializeCreateAction()
    {
        $this->setTypeConverterConfigurationForImageUpload('newKostenstelle');
    }
    
    /**
     * Set TypeConverter option for image upload
     */
    public function initializeUpdateAction()
    {
        $this->setTypeConverterConfigurationForImageUpload('kostenstelle');
    }
    
    /**
     *
     */
    protected function setTypeConverterConfigurationForImageUpload($argumentName)
    {
        $uploadConfiguration = [
            UploadedFileReferenceConverter::CONFIGURATION_ALLOWED_FILE_EXTENSIONS => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
            UploadedFileReferenceConverter::CONFIGURATION_UPLOAD_FOLDER => '1:/user_upload/kostenstellen/',
        ];
        /** @var PropertyMappingConfiguration $newKostenstelleConfiguration */
        $newKostenstelleConfiguration = $this->arguments[$argumentName]->getPropertyMappingConfiguration();
        for($i= 0; $i < 99; $i++) {
            $newKostenstelleConfiguration->forProperty('images.'.$i)
                ->setTypeConverterOptions(
                    'Pmwebdesign\\Staffm\\Property\\TypeConverter\\UploadedFileReferenceConverter',
                    $uploadConfiguration
                );
        }

    }

    /**
     * Export data to Excel
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle 
     * @return void
     */
    public function exportAction(\Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle = NULL)
    {
        if ($this->request->hasArgument('searching')) {
            $search = $this->request->getArgument('searching');
        }

        $aktpfad = $_SERVER['DOCUMENT_ROOT'];
        $filePath = $aktpfad . "/uploads/tx_staffm/export.xlsx";

        $limit = 0;

        $_oPHPExcel = new Spreadsheet();
        $_oExcelWriter = new Xlsx($_oPHPExcel);

        // Show Cost centers?
        if ($kostenstelle == NULL) {
            $kostenstellen = $this->kostenstelleRepository->findSearchForm($search, $limit);

            // Create new Worksheet
            $myWorkSheet = new Worksheet($_oPHPExcel, 'Kostenstellen');
            $_oPHPExcel->addSheet($myWorkSheet, 0);
            $sheetIndex = $_oPHPExcel->getIndex(
                    $_oPHPExcel->getSheetByName('Worksheet')
            );
            // Delete standard worksheet
            $_oPHPExcel->removeSheetByIndex($sheetIndex);

            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'Kostenstelle');
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'Bezeichnung');
            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'Verantwortlicher');
            
            for ($i = 0; $i < count($kostenstellen); $i++) {
                $kostenstelle = new \Pmwebdesign\Staffm\Domain\Model\Kostenstelle();
                $kostenstelle = $kostenstellen[$i];
                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $i + 2, $kostenstelle->getNummer());
                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $i + 2, $kostenstelle->getBezeichnung());
                if ($kostenstelle->getVerantwortlicher() != null) {
                    $vera = $kostenstelle->getVerantwortlicher()->getLastName() . " " . $kostenstelle->getVerantwortlicher()->getFirstName();
                    $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $i + 2, $vera);
                }
            }
        } else {
            // Show employees
            $mitarbeiters = $kostenstelle->getMitarbeiters();

            // Create new Worksheet
            $myWorkSheet = new Worksheet($_oPHPExcel, 'Mitarbeiter');
            $_oPHPExcel->addSheet($myWorkSheet, 0);
            $sheetIndex = $_oPHPExcel->getIndex(
                    $_oPHPExcel->getSheetByName('Worksheet')
            );
            // Delete standard worksheet
            $_oPHPExcel->removeSheetByIndex($sheetIndex);

            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "Mitarbeiterliste für die Kostenstelle " . $kostenstelle->getNummer() . " " . $kostenstelle->getBezeichnung());

            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 2, "(Kst-Verantwortlicher: " . $kostenstelle->getVerantwortlicher()->getLastName() . " " . $kostenstelle->getVerantwortlicher()->getFirstName() . ")");
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
            foreach ($mitarbeiters as $mitarbeiter) {
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

        // Save Excel file on server	
        $_oExcelWriter->save($filePath);
        unset($_oExcelWriter);
        unset($_oPHPExcel);

        // "Save us" for users
        $size = filesize($filePath);        
        header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-disposition: attachment; filename=\"export.xlsx\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: $size");        
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        ob_clean(); // Very important for excel file
        flush(); // Very important for excel file
        readfile($filePath);

        // Delete Excel file on server
        unlink($filePath);
    }

    /**
     * List Cost centers
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
     * @return void
     */
    public function listAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter = NULL)
    {
        // Search word?
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
            $this->view->assign('search', $search);  
        } else {
            $search = NULL;
        }
       
        if ($this->request->hasArgument('key')) {
            $key = $this->request->getArgument('key');
            $this->view->assign('key', $key);            
        }
        
        // Choose cost center from cost center employee edit        
        if ($this->request->hasArgument('kst')) {
            $kst = $this->request->getArgument('kst');
            if ($kst == "kst") {
                $this->view->assign('kst', $kst);
            }
        }
        // No caching?
        if ($this->request->hasArgument('cache')) {
            $cache = $this->request->getArgument('cache');            
        } 
        
        if($key == "auswahl") {
            $limit = 0;
            $kostenstelles = $this->kostenstelleRepository->findSearchForm($search, $limit);
            $this->view->assign('kostenstelles', $kostenstelles);
            $this->view->assign('mitarbeiter', $mitarbeiter);
        } else {
            // Clicked char?
            if ($this->request->hasArgument('@widget_0')) {
                $widget = $this->request->getArgument('@widget_0');                
                $char = $widget["char"];
            }
            $maid = "";
            if ($this->request->hasArgument('maid')) {
                $maid = $this->request->getArgument('maid');
            }
            
            $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);

            // No cache flag? (Example cost center was deleted and the info message is shown in the list view)       
            if ($cache != "notcache" && $search == "") {
                // Cache exist?       
                if (($output = $cacheService->getCache($this->request->getControllerActionName(), $this->request->getControllerName(), $char, $maid, 0)) != NULL) {
                    // Show Cache-Page
                    return $output;
                }
            }

            $limit = 0;
            $kostenstelles = $this->kostenstelleRepository->findSearchForm($search, $limit);

            // Previous search?
            if ($this->request->hasArgument('standardsearch')) {
                $standardsearch = $this->request->getArgument('standardsearch');
            }

            $this->view->assign('standardsearch', $standardsearch);
            $this->view->assign('kostenstelles', $kostenstelles);
            $this->view->assign('mitarbeiter', $mitarbeiter);           
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
    }
    
    /**
     * List of cost centers for assign to a representation
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Representation $representation
     */
    public function listChooseAction(\Pmwebdesign\Staffm\Domain\Model\Representation $representation)
    {        
        if($this->request->hasArgument("key")) {
            $this->view->assign('key', $this->request->getArgument("key"));
        }
        if($this->request->hasArgument("userKey")) {
            $this->view->assign('userKey', $this->request->getArgument("userKey"));
        }
        
        $kostenstellen = $this->kostenstelleRepository->findCostCentersFromResponsible($representation->getEmployee());
        $this->view->assign('kostenstellen', $kostenstellen);
        $this->view->assign('representation', $representation);
    }

    /**
     * Single view for cost center
     * 
     * @param integer $kostenstelle
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
     * @return void
     */
    public function showAction($kostenstelle, \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter = NULL)
    {
        if ($mitarbeiter != NULL) {
            $key = $this->request->getArgument('key');
            $this->view->assign('key', $key);            
            $this->view->assign('mitarbeiter', $mitarbeiter);
        }
        
        // Get logged in user
        $aktuser = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter();
        $aktuser = $this->objectManager->
                get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->
                findOneByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        // If user is logged in, get cost centers who user is responsible
        if ($aktuser != NULL) {
            $this->view->assign('aktuser', $aktuser);
            // Cost Centers of responsible            
            $kostenstellen = $this->objectManager->
                    get('Pmwebdesign\\Staffm\\Domain\\Repository\\KostenstelleRepository')->
                    findByVerantwortlicher($GLOBALS['TSFE']->fe_user->user['uid']);
            $this->view->assign('kostenstellen', $kostenstellen);
        }
        
        $kostenstelle = $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\KostenstelleRepository')->findOneByUid($kostenstelle); 

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

        $this->view->assign('kostenstelle', $kostenstelle);
    }

    /**
     * Assign an employee to a cost center
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
     * @param \Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle
     */
    public function chooseAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter, 
            \Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle)
    {
        $mitarbeiter->setKostenstelle($kostenstelle);
        $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->update($mitarbeiter);
          
        // Delete Cache from cost center
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);        
        $cacheService->deleteCaches($kostenstelle->getBezeichnung(), "show", $this->request->getControllerName(), $kostenstelle->getUid());
                
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
        } else {
            $search = "";
        }
        
        if ($this->request->hasArgument('kst')) {
            $kst = $this->request->getArgument('kst');
            // Employee edits from cost center single view and choose a cost center?
            if ($kst == "kst") {
                $this->addFlashMessage('Kostenstellenverantwortlichen zugewiesen!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
                $this->forward('editKst', 'Mitarbeiter', NULL, array('ma' => $mitarbeiter,
                    'kst' => $kst, 'kostenstelle' => $kostenstelle));
            } else {  
                // Cost center selection in Employee Edit Form
                $this->addFlashMessage('Kostenstelle zugewiesen!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
                $this->forward('edit', 'Mitarbeiter', NULL, array('mitarbeiter' => $mitarbeiter->getUid(), 'search' => $search));
            }
        } else {
            $this->addFlashMessage('Kostenstelle zugewiesen!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
            $this->forward('edit', 'Mitarbeiter', NULL, array('mitarbeiter' => $mitarbeiter->getUid(), 'search' => $search));
        }
    }

    /**
     * Delete the cost center of an employee
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter	
     */
    public function deleteKstAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter)
    {
        $this->addFlashMessage('Die Kostenstelle "'.$mitarbeiter->getKostenstelle()->getNummer().' '.$mitarbeiter->getKostenstelle()->getBezeichnung().'" wurde vom Mitarbeiter '.$mitarbeiter->getFirstName().' '.$mitarbeiter->getLastName().' entfernt!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
        }

        $mitarbeiter->setKostenstelle(NULL);
        $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->update($mitarbeiter);        
        $this->redirect('edit', 'Mitarbeiter', NULL, array('mitarbeiter' => $mitarbeiter, 'search' => $search));
    }

    /**
     * Form new for a cost center
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Kostenstelle $newKostenstelle
     * @ignorevalidation $newKostenstelle
     * @return void
     */
    public function newAction(\Pmwebdesign\Staffm\Domain\Model\Kostenstelle $newKostenstelle = NULL)
    {
        $this->view->assign('newKostenstelle', $newKostenstelle);
    }

    /**
     * Create the new cost center
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Kostenstelle $newKostenstelle
     * @return void
     */
    public function createAction(\Pmwebdesign\Staffm\Domain\Model\Kostenstelle $newKostenstelle)
    {        
        $this->addFlashMessage('Die Kostenstelle "'.$newKostenstelle->getNummer().' '.$newKostenstelle->getBezeichnung().'" wurde angelegt!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->kostenstelleRepository->add($newKostenstelle); 
        $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')->persistAll();
        
        // Delete Caches
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);
        $cacheService->deleteCaches($newKostenstelle->getBezeichnung(), "list", $this->request->getControllerName(), 0);        
        
        $this->redirect('edit', 'Kostenstelle', NULL, array('kostenstelle' => $newKostenstelle));
    }

    /**
     * Form edit for cost center
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle
     * @ignorevalidation $kostenstelle
     * @return void
     */
    public function editAction(\Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle)
    {
        $this->view->assign('kostenstelle', $kostenstelle);
    }

    /**
     * Update a cost center
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle
     * @return void
     */
    public function updateAction(\Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle)
    {        
        $this->addFlashMessage('Kostenstelle aktualisiert!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->kostenstelleRepository->update($kostenstelle);   
        
        // Delete Caches
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);
        $cacheService->deleteCaches($kostenstelle->getBezeichnung(), "list", $this->request->getControllerName(), 0);
        $cacheService->deleteCaches($kostenstelle->getBezeichnung(), "show", $this->request->getControllerName(), $kostenstelle->getUid());
        
        $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')->persistAll();       
        $this->redirect('edit', 'Kostenstelle', NULL, array('kostenstelle' => $kostenstelle));
    }

    /**
     * Delete a cost center
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle
     * @return void
     */
    public function deleteAction(\Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle)
    {
        // Delete cost center of employees
        foreach ($this->mitarbeiterRepository->findKostenstellenMitarbeiter($kostenstelle) as $m) {
            $m->setKostenstelle(NULL);
            $this->mitarbeiterRepository->update($m);            
        }

        $this->addFlashMessage('Kostenstelle gelöscht!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->kostenstelleRepository->remove($kostenstelle);
        
        // Delete Caches
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);
        $cacheService->deleteCaches($kostenstelle->getBezeichnung(), "list", $this->request->getControllerName(), 0);
        $cacheService->deleteCaches($kostenstelle->getBezeichnung(), "show", $this->request->getControllerName(), $kostenstelle->getUid());
        
        $this->redirect('list', 'Kostenstelle', NULL, array('cache' => 'notcache'));
    }

    /**
     * Delete the cost center responsible
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle
     * @return void
     */
    public function deleteKstVerantwortlicherAction(\Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle)
    {
        $kostenstelle->setVerantwortlicher(NULL);
        $this->kostenstelleRepository->update($kostenstelle);
        $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')->persistAll();
        
        // Delete Caches
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);
        $cacheService->deleteCaches($kostenstelle->getBezeichnung(), "list", $this->request->getControllerName(), 0);
        $cacheService->deleteCaches($kostenstelle->getBezeichnung(), "show", $this->request->getControllerName(), $kostenstelle->getUid());

        $this->redirect('edit', 'Kostenstelle', NULL, array('kostenstelle' => $kostenstelle));
    }
}
