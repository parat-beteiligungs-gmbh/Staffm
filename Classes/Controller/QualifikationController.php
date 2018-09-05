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
 * QualifikationController
 */
class QualifikationController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     *
     * @var \TYPO3\CMS\Core\Cache\CacheManager 
     */
    protected $cache;

    /**
     * qualifikationRepository
     * 
     * @var \Pmwebdesign\Staffm\Domain\Repository\QualifikationRepository	
     */
    protected $qualifikationRepository = NULL;

    /**
     * qualilogRepository
     * 
     * @var \Pmwebdesign\Staffm\Domain\Repository\QualilogRepository	
     */
    protected $qualilogRepository = NULL;

    /**
     *
     * @var \Pmwebdesign\Staffm\Domain\Repository\MitarbeiterRepository	
     */
    protected $mitarbeiterRepository = NULL;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\MitarbeiterQualifiaktion>
     */
    protected $arrMitarbeiterQualifikation = NULL;

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
     * action export 
     * Daten in Excel exportieren           
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

        // Prüfen ob Qualifikationen, oder Mitarbeiter ausgegeben werden sollen
        if ($qualifikation == NULL) {

            // Qualifikationen sollen ausgegeben werden
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
            //$_oPHPExcel->getSheetByName("Test")->setCellValue('A1', 'Titel');
            for ($i = 0; $i < count($qualifikationen); $i++) {
                $qualifikation = new \Pmwebdesign\Staffm\Domain\Model\Qualifikation();
                $qualifikation = $qualifikationen[$i];
                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $i + 2, $qualifikation->getBezeichnung());
                echo $qualifikation->getBezeichnung() . " ";
                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $i + 2, $qualifikation->getBeschreibung());
                // Letzten Bearbeiter ermitteln
                $qualil = new \Pmwebdesign\Staffm\Domain\Model\Qualilog();
                $qualilogs = $qualifikation->getQualilogs();

                foreach ($qualilogs as $value) {
                    $qualil = $value;
                    break;
                }
                // Mitarbeiter gelöscht?
                if ($qualil->getBearbeiter() != NULL) {
                    $bearbeiter = $qualil->getBearbeiter()->getLastName() . " " . $qualil->getBearbeiter()->getFirstName();
                } else {
                    $bearbeiter = "";
                }

                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $i + 2, $bearbeiter);
                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $i + 2, count($qualifikation->getMitarbeiters()));
                $stringMitarbeiter = "";
                $mitarbeiterliste = $qualifikation->getMitarbeiters();
                if ($mitarbeiterliste != NULL) {
                    foreach ($mitarbeiterliste as $m) {
                        if ($m != NULL) {
                            $mit = "" . $m->getLastName() . " " . $m->getFirstName() . ",";
                            $stringMitarbeiter = $stringMitarbeiter . $mit;
                        }
                    }
                }
                $_oPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $i + 2, $stringMitarbeiter);
                echo "<br />";
            }
        } else {
            // Mitarbeiter sollen ausgegeben werden
            $mitarbeiters = $qualifikation->getMitarbeiters();

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

        /* Caching Framework */
        $speak = $GLOBALS['TSFE']->sys_language_uid; // Language Index
        $cachename = $speak."listQualiIdentifier";
        $keyforcache = array('normal');
        // Char or employee id?
        if ($char != "" || $maid == "maid") {
            // All clicked?
            if ($char == '%') {
                $search = "";
                $char = "All";
                $key = "all";
            } elseif ($char <> '') {
                $search = "";
                // No, a other char is clicked
                $char = $char;
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

        $limit = 0;
        $qualifikations = $this->qualifikationRepository->findSearchForm($search, $limit);
        
        // Prüft ob eine Qualifikationsauwahl erfolgt (bei Mitarbeiterzuordnung)
        if ($this->request->hasArgument('key')) {
            $key = $this->request->getArgument('key');
            if ($key == 'auswahl') {
                // Ja, Qualifikationsauswahl
                // Qualifikationen abfragen sonst nicht zugeordnet
                //$mq = $this->mitarbeiterqualifikationRepository->findSearchForm($mitarbeiter->getUid());
                //$mitarbeiter->setMitarbeiterQualifikationen($mq);			
                //echo "<script> alert('Key: ".$key."'); </script>";			
            }
            $this->view->assign('key', $key);
        }        

        if ($this->request->hasArgument('berechtigung')) {
            $berechtigung = $this->request->getArgument('berechtigung');
            $this->view->assign('berechtigung', $berechtigung);
        }

        $this->view->assign('qualifikations', $qualifikations);
        $this->view->assign('mitarbeiter', $mitarbeiter);
        $this->view->assign('search', $search);
        if ($maid != "") {
            $this->view->assign('maid', $maid);
        }
        
        // Search exist?
        if ($search <> "") {
            // Yes, no Cache is needed
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
     * Listet die Mitarbeiter des Vorgesetzten auf anhand der Kst, für die
     * er verantwortlich ist
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

        // Überprüfen ob Argument gesetzt wurde
        /* Gelöst: Bringt Fehler bei Suchbegriffen im Frontend! 2016-09-01
          Gelöst: if($key == 'auswahl') eingefügt */
        // Prüft ob eine Qualifikationsauwahl erfolgt (bei Mitarbeiterzuordnung)
        if ($this->request->hasArgument('key')) {
            $key = $this->request->getArgument('key');
            $this->view->assign('key', $key);
        }

        // Angemeldeten User ermitteln
        $aktuser = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter();
        $aktuser = $this->objectManager->
                get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->
                findOneByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        // Wenn User angemeldet an View übergeben
        if ($aktuser != NULL) {
            $this->view->assign('aktuser', $aktuser);

            // Mitarbeiter des Kostenstellenverantwortlichen ermitteln
            $mitarbeiter = $this->mitarbeiterRepository->findMitarbeiterVonVorgesetzten(Null, $aktuser);

            $this->view->assign('mitarbeiters', $mitarbeiter);
        } else {
            $this->view->assign('mitarbeiter', $mitarbeiter);
        }

        // MAID zum zurückkehren der vorher ausgewählten Qualifikation
        if ($this->request->hasArgument('maid')) {
            $maid = $this->request->getArgument('maid');
            $this->view->assign('maid', $maid);
        }

        $this->view->assign('qualifikations', $qualifikations);
        $this->view->assign('search', $search);
    }

    /**
     * action show
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
        }
        // Angemeldeten User ermitteln
        $aktuser = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter();
        $aktuser = $this->objectManager->
                get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->
                findOneByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        // Wenn User angemeldet an View übergeben
        if ($aktuser != NULL) {
            $this->view->assign('aktuser', $aktuser);
            //echo "<script> alert('User: ".$aktuser->getLastName()."'); </script>";
            // Kostenstellen des angemeldeten Users ermitteln            
            $kostenstellen = $this->objectManager->
                    get('Pmwebdesign\\Staffm\\Domain\\Repository\\KostenstelleRepository')->
                    findByVerantwortlicher($GLOBALS['TSFE']->fe_user->user['uid']);
            $this->view->assign('kostenstellen', $kostenstellen);
        }

        // Suchwort?
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
            $this->view->assign('search', $search);
        }

        // Ursprüngliche Suche?
        if ($this->request->hasArgument('standardsearch')) {
            $standardsearch = $this->request->getArgument('standardsearch');
            $this->view->assign('standardsearch', $standardsearch);
        }
        $qualifikation = $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\QualifikationRepository')->findOneByUid($qualifikation);
        $this->view->assign('qualifikation', $qualifikation);
    }

    /**
     * action new
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
     * action create
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Qualifikation $newQualifikation
     * @return void
     */
    public function createAction(\Pmwebdesign\Staffm\Domain\Model\Qualifikation $newQualifikation)
    {
        // Log erzeugen
        $qualil = new \Pmwebdesign\Staffm\Domain\Model\Qualilog();
        $qualil->setQualifikation($newQualifikation);
        $qualil->setBezeichnung($newQualifikation->getBezeichnung());
        $qualil->setBeschreibung($newQualifikation->getBeschreibung());

        // Bearbeitenden User ermitteln		
        $userid = $GLOBALS['TSFE']->fe_user->user['uid'];
        // Frontend-User?
        if ($userid != null) {
            // Wenn Frontend-User
            $qualil->setBearbeiter($this->objectManager->get(
                            'Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository'
                    )->findOneByUid(
                            $GLOBALS['TSFE']->fe_user->user['uid']
                    )
            );
        } else {
            // Wenn Backend-User
            $qualil->setBearbeiter($this->objectManager->get(
                            'Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository'
                    )->findOneByUsername(
                            $GLOBALS['BE_USER']->user['username']
                    )
            );
        }
        $qualil->setStatus("angelegt");
        //print_r($qualil->getBezeichnung()."<br />".$qualil->getBeschreibung()."<br />".$qualil->getBearbeiter()->getLastName());
        $this->qualilogRepository->add($qualil);

        $this->addFlashMessage('Qualifikation angelegt!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->qualifikationRepository->add($newQualifikation);
        
         // Delete Caches
        $char = strtoupper(substr($newQualifikation->getBezeichnung(), 0, 1));
        $this->cache->remove("0listQualiIdentifier");
        $this->cache->remove("0listQualiIdentifierAll");
        $this->cache->remove("0listQualiIdentifier".$char);
        $this->cache->remove("0listQualiIdentifierAdm");
        $this->cache->remove("0listQualiIdentifierAllAdm");
        $this->cache->remove("0listQualiIdentifier".$char."Adm");
        
        $this->cache->remove("1listQualiIdentifier");
        $this->cache->remove("1listQualiIdentifierAll");
        $this->cache->remove("1listQualiIdentifier".$char);
        $this->cache->remove("1listQualiIdentifierAdm");
        $this->cache->remove("1listQualiIdentifierAllAdm");
        $this->cache->remove("1listQualiIdentifier".$char."Adm");
        
        $this->redirect('list');
    }

    /**
     * action edit
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
     * action update
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualifikation
     * @return void
     */
    public function updateAction(\Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualifikation)
    {
        // Log erzeugen
        $qualil = new \Pmwebdesign\Staffm\Domain\Model\Qualilog();
        $qualil->setQualifikation($qualifikation);
        $qualil->setBezeichnung($qualifikation->getBezeichnung());
        $qualil->setBeschreibung($qualifikation->getBeschreibung());
        //$mitarbeiter = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter();
        // Bearbeitenden User ermitteln		
        $userid = $GLOBALS['TSFE']->fe_user->user['uid'];
        // Frontend-User?
        if ($userid != null) {
            // Wenn Frontend-User
            $qualil->setBearbeiter($this->objectManager->get(
                            'Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository'
                    )->findOneByUid(
                            $GLOBALS['TSFE']->fe_user->user['uid']
                    )
            );
        } else {
            // Wenn Backend-User
            $qualil->setBearbeiter($this->objectManager->get(
                            'Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository'
                    )->findOneByUsername(
                            $GLOBALS['BE_USER']->user['username']
                    )
            );
        }
        $qualil->setStatus("aktualisiert");
        $this->objectManager->get(
                'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager'
        )->persistAll();
        $this->qualilogRepository->add($qualil);

        if ($this->request->hasArgument('mitarbeiters')) {
            $mitarbeiters = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();

            $ma = $this->request->getArgument('mitarbeiters');
            // Ausgewählte Checkboxen auslesen in Array 
            // Aktuelle Objekte suchen zu dem Text
            foreach ($ma as $m) {
                $mitarbeiter = $this->objectManager->get(
                                'Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository'
                        )->findOneByUid($m);
                // Objekte in Array speichern
                $mitarbeiters->attach($mitarbeiter);
            }
            $qualifikation->setMitarbeiters($mitarbeiters);
            if (count($qualifikation->getMitarbeiters()) > 0) {
                $this->addFlashMessage('Mitarbeiter wurden der Qualifikation "' . $qualifikation->getBezeichnung() . '" zugeordnet!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
            } else {
                $this->addFlashMessage('Der Qualifikation "' . $qualifikation->getBezeichnung() . '" wurden keine Mitarbeiter zugeordnet!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            }
        } else {
            $this->addFlashMessage('Die Qualifikation "' . $qualifikation->getBezeichnung() . '" wurde aktualisiert!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        }
        $this->qualifikationRepository->update($qualifikation);
        
        // Delete Caches
        $char = strtoupper(substr($qualifikation->getBezeichnung(), 0, 1));
        $this->cache->remove("0listQualiIdentifier");
        $this->cache->remove("0listQualiIdentifierAll");
        $this->cache->remove("0listQualiIdentifier".$char);
        $this->cache->remove("0listQualiIdentifierAdm");
        $this->cache->remove("0listQualiIdentifierAllAdm");
        $this->cache->remove("0listQualiIdentifier".$char."Adm");       
        
        $this->cache->remove("1listQualiIdentifier");
        $this->cache->remove("1listQualiIdentifierAll");
        $this->cache->remove("1listQualiIdentifier".$char);
        $this->cache->remove("1listQualiIdentifierAdm");
        $this->cache->remove("1listQualiIdentifierAllAdm");
        $this->cache->remove("1listQualiIdentifier".$char."Adm");     
        
        $this->redirect('edit', 'Qualifikation', NULL, array('qualifikation' => $qualifikation, 'search' => $search, 'berechtigung' => $berechtigung));
    }

    /**
     * action delete
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualifikation
     * @return void
     */
    public function deleteAction(\Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualifikation)
    {
        // Log erzeugen
        $qualil = new \Pmwebdesign\Staffm\Domain\Model\Qualilog();
        $qualil->setQualifikation($qualifikation);
        $qualil->setBezeichnung($qualifikation->getBezeichnung());
        $qualil->setBeschreibung($qualifikation->getBeschreibung());

        // Bearbeitenden User ermitteln		
        $userid = $GLOBALS['TSFE']->fe_user->user['uid'];
        // Frontend-User?
        if ($userid != null) {
            // Wenn Frontend-User
            $qualil->setBearbeiter($this->objectManager->get(
                            'Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository'
                    )->findOneByUid(
                            $GLOBALS['TSFE']->fe_user->user['uid']
                    )
            );
        } else {
            // Wenn Backend-User
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

        // Qualifikation von den zugehörigen Mitarbeitern entfernen
        $qualifikation->deleteMitarbeiters();
        $this->qualifikationRepository->update($qualifikation);

        $this->objectManager->get(
                'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager'
        )->persistAll();
        
        // Delete Caches
        $char = strtoupper(substr($qualifikation->getBezeichnung(), 0, 1));
        $this->cache->remove("0listQualiIdentifier");
        $this->cache->remove("0listQualiIdentifierAll");
        $this->cache->remove("0listQualiIdentifier".$char);
        $this->cache->remove("0listQualiIdentifierAdm");
        $this->cache->remove("0listQualiIdentifierAllAdm");
        $this->cache->remove("0listQualiIdentifier".$char."Adm");        
        
        $this->cache->remove("1listQualiIdentifier");
        $this->cache->remove("1listQualiIdentifierAll");
        $this->cache->remove("1listQualiIdentifier".$char);
        $this->cache->remove("1listQualiIdentifierAdm");
        $this->cache->remove("1listQualiIdentifierAllAdm");
        $this->cache->remove("1listQualiIdentifier".$char."Adm");      

        $this->addFlashMessage('Qualifikation gelöscht!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->qualifikationRepository->remove($qualifikation);
        $this->redirect('list');
    }

    /**
     * action choose
     * Auswahl der Qualifikationen für den Mitarbeiter
     * 	
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter 
     * @return \Pmwebdesign\Staffm\Domain\Repository\Mitarbeiterqualifikation
     */
    public function chooseAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter = NULL)
    {
        if ($this->request->hasArgument('mitarbeiter')) {
            // Ausgewählte Checkboxen auslesen in Array
            $qua = $this->request->getArgument('qualifikationen');
            $arrMitarbeiterQualifikation = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
            // Aktuelle Objekte suchen zu dem Text
            foreach ($qua as $q) {

                $mitarbeiterQualifikation = $this->objectManager->get(
                                'Pmwebdesign\\Staffm\\Domain\\Repository\\QualifikationRepository'
                        )->findOneByUid($q);
                // Objekte in Array speichern
                $arrMitarbeiterQualifikation->attach($mitarbeiterQualifikation);
            }



            $mitarbeiter->setMitarbeiterQualifikationen($arrMitarbeiterQualifikation);
            $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->update($mitarbeiter);
            $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')->persistAll();

            $this->addFlashMessage('Die Qualifikationen des Mitarbeiters wurden aktualisiert!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
            $this->forward('edit', 'Mitarbeiter', NULL, array('mitarbeiter' => $mitarbeiter));
        }
    }

    /**
     * action choose
     * Massenuswahl der Qualifikationen für den Mitarbeiter, können nur
     * Vorgesetzte ausführen
     * 	
     * @return \Pmwebdesign\Staffm\Domain\Repository\Mitarbeiterqualifikation
     */
    public function chooselistAction()
    {        
        // Element Checkbox vorhanden?
        if ($this->request->hasArgument('qualifikationen')) {            
            // Ausgewählte Checkboxen auslesen in Array
            $qua = $this->request->getArgument('qualifikationen');    
            //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($qua); // TODO: Test Ok
            // Alle "vorherigen" Mitarbeiterqualifikationen der Kst Mitarbeiter auslesen
            // Angemeldeten User ermitteln            
            $aktuser = $this->objectManager->
                    get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->
                    findOneByUid($GLOBALS['TSFE']->fe_user->user['uid']);
            // Wenn User angemeldet an View übergeben
            if ($aktuser != NULL) {
                // Mitarbeiter des Kostenstellenverantwortlichen ermitteln
                $mitarbeiters = $this->mitarbeiterRepository->findMitarbeiterVonVorgesetzten(Null, $aktuser);
            }

            // Alle Qualifikationen durchlaufen, $q = Qualifikation, $value = Array von Mitarbeitern
            foreach ($qua as $q => $value) {
                // Qualifikation über id ($q) ermitteln                    
                $qualifikation = $this->objectManager->get(
                                'Pmwebdesign\\Staffm\\Domain\\Repository\\QualifikationRepository'
                        )->findOneByUid($q);

                // Mitarbeiterarray durchlaufen
                $arrMitarbeiter = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
                // Employees exist in Qualification?
                if($value != "") {
                    // Yes, all employees attach to array
                    foreach ($value as $ma) {        
                        $mitarbeiter = $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->findOneByUid($ma);
                        // Attach employee to array
                        $arrMitarbeiter->attach($mitarbeiter);
                    }
                }
                $qualifikation->setMitarbeiters($arrMitarbeiter);                
                $this->qualifikationRepository->update($qualifikation);                
            }
            $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')->persistAll();
        }
        $qualifikationen = $this->qualifikationRepository->findSearchForm(NULL, 0);
        $this->addFlashMessage('Die Mitarbeiter-Qualifikationen wurden aktualisiert!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        //$this->forward('listVgs', 'Qualifikation', NULL, NULL); 
        $this->redirect('listVgs', 'Qualifikation', NULL, array('qualifikations' => $qualifikationen));
    }

}
