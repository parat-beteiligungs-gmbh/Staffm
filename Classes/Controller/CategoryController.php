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
 * Category Controller
 */
class CategoryController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * Category Repository
     * 
     * @var \Pmwebdesign\Staffm\Domain\Repository\CategoryRepository	 
     */
    protected $categoryRepository = NULL;

    /**
     * 
     * @param \Pmwebdesign\Staffm\Domain\Repository\CategoryRepository $categoryRepository
     */
    public function injectCategoryRepository(\Pmwebdesign\Staffm\Domain\Repository\CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
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
        $this->view->assign('menuname', 'Category');
    }

    /**
     * Export data to Excel        
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Category $category
     * @return void
     */
    public function exportAction(\Pmwebdesign\Staffm\Domain\Model\Category $category = NULL)
    {
        if ($this->request->hasArgument('searching')) {
            $search = $this->request->getArgument('searching');
        }

        $aktpfad = $_SERVER['DOCUMENT_ROOT'];
        $filePath = $aktpfad . "/uploads/tx_staffm/export.xlsx";

        $limit = 0;

        $_oPHPExcel = new Spreadsheet();
        $_oExcelWriter = new Xlsx($_oPHPExcel);

        // Check output, output are categorys, or employees
        if ($category == NULL) {
            // Output, are categorys
            $categoryen = $this->categoryRepository->findSearchForm($search, $limit);

            // Create new Worksheet
            $myWorkSheet = new Worksheet($_oPHPExcel, 'Kategorien');
            $_oPHPExcel->addSheet($myWorkSheet, 0);
            $sheetIndex = $_oPHPExcel->getIndex(
                    $_oPHPExcel->getSheetByName('Worksheet')
            );
            // Delete standard worksheet
            $_oPHPExcel->removeSheetByIndex($sheetIndex);

            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'Kategorien');
            $i = 0;
            foreach ($categoryen as $category) {
                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $i + 2, $category->getName());
                $i++;
            }
        } else {
            // Output are employees
            $mitarbeiters = $category->getMitarbeiters();

            // Create new Worksheet
            $myWorkSheet = new Worksheet($_oPHPExcel, 'Mitarbeiter');
            $_oPHPExcel->addSheet($myWorkSheet, 0);
            $sheetIndex = $_oPHPExcel->getIndex(
                    $_oPHPExcel->getSheetByName('Worksheet')
            );
            // Delete standard worksheet
            $_oPHPExcel->removeSheetByIndex($sheetIndex);

            $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "Mitarbeiterliste für die Category " . $category->getName());

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
        header("Content-discategory: attachment; filename=\"export.xlsx\"");
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
     * List of Categories
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $employee
     * @param \Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualification
     * @return void
     */
    public function listAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $employee = NULL,
            \Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualification = NULL)
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
        
        /* @var $cacheService \Pmwebdesign\Staffm\Domain\Service\CacheService */
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);        
        
        // No cache flag? (Example category was deleted and the info message is shown in the list view)
        if ($cache != "notcache" && $search == "") {
            // Cache exist?       
            if (($output = $cacheService->getCache($this->request->getControllerActionName(), $this->request->getControllerName(), $char, $maid, 0)) != NULL) {
                // Show Cache-Page
                return $output;
            }
        }
        
        $limit = 0;
        $categories = $this->categoryRepository->findSearchForm($search, $limit);
        
        if ($this->request->hasArgument('key')) {
            $key = $this->request->getArgument('key');
            $this->view->assign('key', $key);
        }
        
        if ($this->request->hasArgument('userKey')) {
            $userKey = $this->request->getArgument('userKey');
            $this->view->assign('userKey', $userKey);
        }

        // Search available?
        if ($this->request->hasArgument('standardsearch')) {
            $standardsearch = $this->request->getArgument('standardsearch');
            $this->view->assign('standardsearch', $standardsearch);
        }

        $this->view->assign('categories', $categories);
        $this->view->assign('qualification', $qualification);
        $this->view->assign('employee', $employee);
        
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
     * Single view of category
     * 
     * @param integer $category
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $employee
     * @return void
     */
    public function showAction($category = 0, \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $employee = NULL)
    {
        $category = $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\CategoryRepository')->findOneByUid($category);
        
        // Previous search?
        if ($this->request->hasArgument('standardsearch')) {
            $standardsearch = $this->request->getArgument('standardsearch');
            $this->view->assign('standardsearch', $standardsearch);
        }        
        $this->view->assign('employee', $employee);
        $this->view->assign('category', $category);
    }

    /**
     * Select a category for an employee
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
     * @param \Pmwebdesign\Staffm\Domain\Model\Category $category
     * @return \Pmwebdesign\Staffm\Domain\Model\Category
     */
    public function chooseAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter, \Pmwebdesign\Staffm\Domain\Model\Category $category)
    {
        $mitarbeiter->setCategory($category);
        $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->update($mitarbeiter);

        // Previous search?
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
        }
        
        // Delete Cache from category
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);        
        $cacheService->deleteCaches($category->getName(), "show", $this->request->getControllerName(), $category->getUid());

        $this->redirect('edit', 'Mitarbeiter', NULL, array('mitarbeiter' => $mitarbeiter, 'search' => $search));
    }

    /**
     * Delete category of an employee
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter	
     */
    public function deleteCategoryAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter)
    {
        $category = $mitarbeiter->getCategory();
        $mitarbeiter->setCategory(NULL);
        $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->update($mitarbeiter);
        
        // Delete Cache from category
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);        
        $cacheService->deleteCaches($category->getName(), "show", $this->request->getControllerName(), $category->getUid());
        
        $this->redirect('edit', 'Mitarbeiter', NULL, array('mitarbeiter' => $mitarbeiter));
    }

    /**
     * New form for category
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Category $newCategory
     * @ignorevalidation $newCategory
     * @return void
     */
    public function newAction(\Pmwebdesign\Staffm\Domain\Model\Category $newCategory = NULL)
    {
        $this->view->assign('newCategory', $newCategory);
    }

    /**
     * Create a new category
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Category $newCategory
     * @return void
     */
    public function createAction(\Pmwebdesign\Staffm\Domain\Model\Category $newCategory)
    {
        $this->addFlashMessage('Kategorie angelegt!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->categoryRepository->add($newCategory);
               
        // Delete Caches
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);
        $cacheService->deleteCaches($newCategory->getName(), "list", $this->request->getControllerName(), 0);  
        
        // Persist object (get uid)
        $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')->persistAll();
        
        $this->redirect('edit', 'Category', NULL, array('category' => $newCategory, 'cache' => 'notcache'));
    }

    /**
     * Edit form for category
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Category $category
     * @ignorevalidation $category
     * @return void
     */
    public function editAction(\Pmwebdesign\Staffm\Domain\Model\Category $category)
    {
        $this->view->assign('category', $category);
    }

    /**
     * Update a category
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Category $category
     * @return void
     */
    public function updateAction(\Pmwebdesign\Staffm\Domain\Model\Category $category)
    {    
        // Get assigned qualifications
        if ($this->request->hasArgument('qualifikationen')) {            
             // QualificationService
            $qualificationService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\QualificationService::class);
            $category->setQualifications($qualificationService->getQualifications($this->request, $this->objectManager));
        }
        
        // Get assigned employees
        if ($this->request->hasArgument('employees')) {            
             /* @var $userService \Pmwebdesign\Staffm\Domain\Service\UserService */
            $userService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\UserService::class);
            $category->setEmployees($userService->getEmployeesOfCheckBoxes($this->request, $this->objectManager));
        }
        
        $this->categoryRepository->update($category);
        $this->addFlashMessage('Die Kategorie "'.$category->getName().'" wurde aktualisiert!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        
        // Delete Caches
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);
        $cacheService->deleteCaches($category->getName(), "list", $this->request->getControllerName(), 0);
        $cacheService->deleteCaches($category->getName(), "show", $this->request->getControllerName(), $category->getUid());
        
        $this->redirect('edit', 'Category', NULL, array('category' => $category));
    }

    /**
     * Delete a category
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Category $category
     * @return void
     */
    public function deleteAction(\Pmwebdesign\Staffm\Domain\Model\Category $category)
    {
        // Delete categories of qualifications
        $category->setQualifications(new \TYPO3\CMS\Extbase\Persistence\ObjectStorage());        
        $this->categoryRepository->update($category);
      
        $this->addFlashMessage('Kategorie gelöscht!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->categoryRepository->remove($category);
        
        // Delete Caches
        $cacheService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\CacheService::class);
        $cacheService->deleteCaches($category->getName(), "list", $this->request->getControllerName(), 0);
        $cacheService->deleteCaches($category->getName(), "show", $this->request->getControllerName(), $category->getUid());
        
        $this->redirect('list', 'Category', NULL, array('cache' => 'notcache'));
    }
}
