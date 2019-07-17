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
use TYPO3\CMS\Core\Messaging\AbstractMessage;

/**
 * Position Controller
 */
class PositionController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * Position Repository
     * 
     * @var \Pmwebdesign\Staffm\Domain\Repository\PositionRepository	 
     */
    protected $positionRepository = NULL;

    /**
     * Employee Repository
     * 
     * @var \Pmwebdesign\Staffm\Domain\Repository\MitarbeiterRepository	
     */
    protected $mitarbeiterRepository = NULL;

    /**
     * 
     * @param \Pmwebdesign\Staffm\Domain\Repository\PositionRepository $positionRepository
     */
    public function injectPositionRepository(\Pmwebdesign\Staffm\Domain\Repository\PositionRepository $positionRepository)
    {
        $this->positionRepository = $positionRepository;
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
     * Initialize action
     * Call before other actions
     * 
     * @return void 
     */
    public function initializeAction()
    {
        /* Caching Framework */
        $this->cache = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager')->getCache('staffm_mycache');
    }

    /**
     * @param \TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view
     * @return void
     */
    protected function initializeView(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view)
    {
        $this->view->assign('menuname', 'Position');
    }

    /**
     * Export data to Excel        
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Position $position
     * @return void
     */
    public function exportAction(\Pmwebdesign\Staffm\Domain\Model\Position $position = NULL)
    {
        if ($this->request->hasArgument('searching')) {
            $search = $this->request->getArgument('searching');
        }

        $aktpfad = $_SERVER['DOCUMENT_ROOT'];
        $filePath = $aktpfad . "/uploads/tx_staffm/export.xlsx";

        $limit = 0;

        $_oPHPExcel = new Spreadsheet();
        $_oExcelWriter = new Xlsx($_oPHPExcel);

        // Check output, output are positions, or employees
        if ($position == NULL) {
            // Output, are positions
            $positionen = $this->positionRepository->findSearchForm($search, $limit);

            // Create new Worksheet
            $myWorkSheet = new Worksheet($_oPHPExcel, 'Positionen');
            $_oPHPExcel->addSheet($myWorkSheet, 0);
            $sheetIndex = $_oPHPExcel->getIndex(
                    $_oPHPExcel->getSheetByName('Worksheet')
            );
            // Delete standard worksheet
            $_oPHPExcel->removeSheetByIndex($sheetIndex);

            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'Positionen');
            $i = 0;
            foreach ($positionen as $position) {
                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $i + 2, $position->getBezeichnung());
                $i++;
            }
        } else {
            // Output are employees
            $mitarbeiters = $position->getMitarbeiters();

            // Create new Worksheet
            $myWorkSheet = new Worksheet($_oPHPExcel, 'Mitarbeiter');
            $_oPHPExcel->addSheet($myWorkSheet, 0);
            $sheetIndex = $_oPHPExcel->getIndex(
                    $_oPHPExcel->getSheetByName('Worksheet')
            );
            // Delete standard worksheet
            $_oPHPExcel->removeSheetByIndex($sheetIndex);

            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "Mitarbeiterliste für die Position " . $position->getBezeichnung());

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
        ob_clean(); // Very important, otherwise a error occurs on the excel file
        flush(); // Very important, otherwise a error occurs on the excel file
        readfile($filePath);

        // Delete Excel file at the server
        unlink($filePath);
    }

    /**
     * List of Positions
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
     * @return void
     */
    public function listAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter = NULL)
    {
        // Search exist?
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
        
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);
        
        // No cache flag? (Example position was deleted and the info message is shown in the list view)
        if ($cache != "notcache" && $search == "") {
            // Cache exist?       
            if (($output = $cacheService->getCache($this->request->getControllerActionName(), $this->request->getControllerName(), $char, $maid, 0)) != NULL) {
                // Show Cache-Page
                return $output;
            }
        }
        
        $limit = 0;
        $positions = $this->positionRepository->findSearchForm($search, $limit);
        
        if ($this->request->hasArgument('key')) {
            $key = $this->request->getArgument('key');
            $this->view->assign('key', $key);
        }

        // Search available?
        if ($this->request->hasArgument('standardsearch')) {
            $standardsearch = $this->request->getArgument('standardsearch');
            $this->view->assign('standardsearch', $standardsearch);
        }

        $this->view->assign('positions', $positions);
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

    /**
     * Single view of position
     * 
     * @param integer $position
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
     * @return void
     */
    public function showAction($position = 0, \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter = NULL)
    {
        if ($mitarbeiter != NULL) {
            $key = $this->request->getArgument('key');
            $this->view->assign('key', $key);
            $position = $mitarbeiter->getPosition();
            $this->view->assign('mitarbeiter', $mitarbeiter);
        } else {
            $position = $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\PositionRepository')->findOneByUid($position);
        }
        
        if ($this->request->hasArgument('userKey')) {
            $this->view->assign('userKey', $this->request->getArgument('userKey'));
        }

        // Previous search?
        if ($this->request->hasArgument('standardsearch')) {
            $standardsearch = $this->request->getArgument('standardsearch');
            $this->view->assign('standardsearch', $standardsearch);
        }

        $this->view->assign('position', $position);
    }

    /**
     * Select a position for an employee
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
     * @param \Pmwebdesign\Staffm\Domain\Model\Position $position
     * @return \Pmwebdesign\Staffm\Domain\Model\Position
     */
    public function chooseAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter, \Pmwebdesign\Staffm\Domain\Model\Position $position)
    {        
        $mitarbeiter->setPosition($position);
        $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->update($mitarbeiter);

        // Previous search?
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
        }
        
        // Delete Cache from position
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);        
        $cacheService->deleteCaches($position->getBezeichnung(), "show", $this->request->getControllerName(), $position->getUid());
        $this->addFlashMessage('Position zu "'.$mitarbeiter->getLastName().' '.$mitarbeiter->getFirstName().'" zugewiesen!', '', AbstractMessage::OK);
        $this->redirect('edit', 'Mitarbeiter', NULL, array('mitarbeiter' => $mitarbeiter, 'search' => $search));
    }

    /**
     * Delete position of an employee
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter	
     */
    public function deletePositionAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter)
    {
        $position = $mitarbeiter->getPosition();
        $mitarbeiter->setPosition(NULL);
        $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->update($mitarbeiter);
        
        // Delete Cache from position
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);        
        $cacheService->deleteCaches($position->getBezeichnung(), "show", $this->request->getControllerName(), $position->getUid());
        
        $this->addFlashMessage('Position von "'.$mitarbeiter->getLastName().' '.$mitarbeiter->getFirstName().'" entfernt!', '', AbstractMessage::ERROR);
        
        $this->redirect('edit', 'Mitarbeiter', NULL, array('mitarbeiter' => $mitarbeiter));
    }

    /**
     * New form for position
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Position $newPosition
     * @ignorevalidation $newPosition
     * @return void
     */
    public function newAction(\Pmwebdesign\Staffm\Domain\Model\Position $newPosition = NULL)
    {
        $this->view->assign('newPosition', $newPosition);
    }

    /**
     * Create a new position
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Position $newPosition
     * @return void
     */
    public function createAction(\Pmwebdesign\Staffm\Domain\Model\Position $newPosition)
    {
        $this->addFlashMessage('Position angelegt!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->positionRepository->add($newPosition);
               
        // Delete Caches
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);
        $cacheService->deleteCaches($newPosition->getBezeichnung(), "list", $this->request->getControllerName(), 0);        
        
        $this->redirect('list', 'Position', NULL, array('cache' => 'notcache'));
    }

    /**
     * Edit form for position
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Position $position
     * @ignorevalidation $position
     * @return void
     */
    public function editAction(\Pmwebdesign\Staffm\Domain\Model\Position $position)
    {
        $this->view->assign('position', $position);
    }

    /**
     * Update a position
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Position $position
     * @return void
     */
    public function updateAction(\Pmwebdesign\Staffm\Domain\Model\Position $position)
    {
        $this->addFlashMessage('Position aktualisiert!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->positionRepository->update($position);
        
        // Delete Caches
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);
        $cacheService->deleteCaches($position->getBezeichnung(), "list", $this->request->getControllerName(), 0);
        $cacheService->deleteCaches($position->getBezeichnung(), "show", $this->request->getControllerName(), $position->getUid());
        
        $this->redirect('edit', 'Position', NULL, array('position' => $position));
    }

    /**
     * Delete a position
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Position $position
     * @return void
     */
    public function deleteAction(\Pmwebdesign\Staffm\Domain\Model\Position $position)
    {
        // Delete positions of employees
        foreach ($this->mitarbeiterRepository->findPositionMitarbeiter($position) as $m) {
            $m->setPosition(NULL);
            $this->mitarbeiterRepository->update($m);
        }
      
        $this->addFlashMessage('Position gelöscht!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->positionRepository->remove($position);
        
        // Delete Caches
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);
        $cacheService->deleteCaches($position->getBezeichnung(), "list", $this->request->getControllerName(), 0);
        $cacheService->deleteCaches($position->getBezeichnung(), "show", $this->request->getControllerName(), $position->getUid());
        
        $this->redirect('list', 'Position', NULL, array('cache' => 'notcache'));
    }

}
