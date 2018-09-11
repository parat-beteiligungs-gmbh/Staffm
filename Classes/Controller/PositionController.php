<?php

/* * *************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2018 Markus Puffer <m.puffer@pm-webdesign.eu>, PM-Webdesign
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

namespace Pmwebdesign\Staffm\Controller;

use PHPOffice\PhpSpreadsheet\Spreadsheet;
use PHPOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PHPOffice\PhpSpreadsheet\Writer\Xlsx;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * PositionController
 */
class PositionController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * positionRepository
     * 
     * @var \Pmwebdesign\Staffm\Domain\Repository\PositionRepository	 
     */
    protected $positionRepository = NULL;

    /**
     * mitarbeiterRepository
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
     * action export 
     * Export data to Microsoft Excel        
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

            //for ($i = 0; $i < count($mitarbeiters); $i++) { // funktioniert nicht!
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

        // Save at for users
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
        ob_clean(); // Very important, otherwise a error occurs on the excel file
        flush(); // Very important, otherwise a error occurs on the excel file
        readfile($filePath);

        // Delete Excel file at the server
        unlink($filePath);
    }

    /**
     * action list
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
        
        // No cache flag? (Example position was deleted and the info message is shown in the list view)
        if ($cache != "notcache") {        
            /* Caching Framework */    
            $speak = $GLOBALS['TSFE']->sys_language_uid; // Language Index
            $cachename = $speak."listPosIdentifier";
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

        // Ursprüngliche Suche?
        if ($this->request->hasArgument('standardsearch')) {
            $standardsearch = $this->request->getArgument('standardsearch');
            $this->view->assign('standardsearch', $standardsearch);
        }

        $this->view->assign('position', $position);
    }

    /**
     * action choose
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
     * @param \Pmwebdesign\Staffm\Domain\Model\Position $position
     * @return \Pmwebdesign\Staffm\Domain\Model\Position
     */
    public function chooseAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter, \Pmwebdesign\Staffm\Domain\Model\Position $position)
    {
        $mitarbeiter->setPosition($position);
        $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->update($mitarbeiter);

        // Ursprüngliche Suche?
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
        }

        $this->redirect('edit', 'Mitarbeiter', NULL, array('mitarbeiter' => $mitarbeiter, 'search' => $search));
    }

    /**
     * action deletePosition
     * Löscht die Position eines Mitarbeiters
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter	
     */
    public function deletePositionAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter)
    {
        $mitarbeiter->setPosition(NULL);
        $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->update($mitarbeiter);
        $this->redirect('edit', 'Mitarbeiter', NULL, array('mitarbeiter' => $mitarbeiter));
    }

    /**
     * action new
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
     * action create
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Position $newPosition
     * @return void
     */
    public function createAction(\Pmwebdesign\Staffm\Domain\Model\Position $newPosition)
    {
        $this->addFlashMessage('Position angelegt!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->positionRepository->add($newPosition);
        
        // Delete Caches
        $char = strtoupper(substr($position->getBezeichnung(), 0, 1));
        $this->cache->remove("0listPosIdentifier");
        $this->cache->remove("1listPosIdentifier");
        $this->cache->remove("0listPosIdentifierAll");
        $this->cache->remove("1listPosIdentifierAll");
        $this->cache->remove("0listPosIdentifier".$char);
        $this->cache->remove("1listPosIdentifier".$char);
        $this->cache->remove("0listPosIdentifierAdm");
        $this->cache->remove("1listPosIdentifierAdm");
        $this->cache->remove("0listPosIdentifierAllAdm");
        $this->cache->remove("1listPosIdentifierAllAdm");
        $this->cache->remove("0listPosIdentifier".$char."Adm");       
        $this->cache->remove("1listPosIdentifier".$char."Adm");       
        
        $this->redirect('list', 'Position', NULL, array('cache' => 'notcache'));
    }

    /**
     * action edit
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
     * action update
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Position $position
     * @return void
     */
    public function updateAction(\Pmwebdesign\Staffm\Domain\Model\Position $position)
    {
        $this->addFlashMessage('Position aktualisiert!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->positionRepository->update($position);
        
        // Delete Caches
        $char = strtoupper(substr($position->getBezeichnung(), 0, 1));
        $this->cache->remove("0listPosIdentifier");
        $this->cache->remove("1listPosIdentifier");
        $this->cache->remove("0listPosIdentifierAll");
        $this->cache->remove("1listPosIdentifierAll");
        $this->cache->remove("0listPosIdentifier".$char);
        $this->cache->remove("1listPosIdentifier".$char);
        $this->cache->remove("0listPosIdentifierAdm");
        $this->cache->remove("1listPosIdentifierAdm");
        $this->cache->remove("0listPosIdentifierAllAdm");
        $this->cache->remove("1listPosIdentifierAllAdm");
        $this->cache->remove("0listPosIdentifier".$char."Adm");
        $this->cache->remove("1listPosIdentifier".$char."Adm");
        
        $this->redirect('edit', 'Position', NULL, array('position' => $position));
    }

    /**
     * action delete
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Position $position
     * @return void
     */
    public function deleteAction(\Pmwebdesign\Staffm\Domain\Model\Position $position)
    {
        // Position löschen von den Mitarbeitern
        foreach ($this->mitarbeiterRepository->findPositionMitarbeiter($position) as $m) {
            $m->setPosition(NULL);
            $this->mitarbeiterRepository->update($m);
        }
      
        $this->addFlashMessage('Position gelöscht!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->positionRepository->remove($position);
        
        // Delete Caches
        $char = strtoupper(substr($position->getBezeichnung(), 0, 1));
        $this->cache->remove("0listPosIdentifier");
        $this->cache->remove("0listPosIdentifierAll");
        $this->cache->remove("0listPosIdentifier".$char);
        $this->cache->remove("0listPosIdentifierAdm");
        $this->cache->remove("0listPosIdentifierAllAdm");
        $this->cache->remove("0listPosIdentifier".$char."Adm");    
        
        $this->cache->remove("1listPosIdentifier");
        $this->cache->remove("1listPosIdentifierAll");
        $this->cache->remove("1listPosIdentifier".$char);
        $this->cache->remove("1listPosIdentifierAdm");
        $this->cache->remove("1listPosIdentifierAllAdm");
        $this->cache->remove("1listPosIdentifier".$char."Adm");       
        
        $this->redirect('list', 'Position', NULL, array('cache' => 'notcache'));
    }

}
