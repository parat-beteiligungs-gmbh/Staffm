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

namespace Pmwebdesign\Staffm\ViewHelpers\Widget\Controller;

/** AtoZNavController
 * Controller für A-Z Suche
 *
 * @author dvpm
 * @version 1.0
 */
class AtoZNavController extends \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController {
    /**
     *       
     */
    protected $objects;
    
    public function initializeAction() {
        $this->objects = $this->widgetConfiguration['objects'];
    }
    
    /**
     * @param string $char
     * @param string $key    
     */
    public function indexAction($char = '%', $key = '') {        
        $search = "";
        if ($this->widgetConfiguration['search'] != "" && $char == '%' && $key == '') {			
                $this->view->assign('contentArguments', array(
                        $this->widgetConfiguration['as'] =>$this->objects
                ));
                $key = "suche";
                $this->view->assign('key', $key);    
                //echo "WidgetKey-suche: ".$key;
        } else {
                // TODO: search leeren funktioniert noch nicht
                $search = $this->widgetConfiguration['search'];
                
                if ($this->widgetConfiguration['search'] != "") {                    
                    $search = "delete";
                }               
                
                $this->view->assign('searchstatus', $search); 

                $query = $this->objects->getQuery();		
                // Nur Objekte mit dem konfigurierten Anfangsbuchstaben char
                $query->matching($query->like($this->widgetConfiguration['property'],$char.'%'));

                if ($key == 'alle' || $key == 'buchs') {
                        // Keine Suchanzahlbegrenzung					
                        $this->view->assign('key', $key);
                        $search = "";
                        $this->view->assign('search', $search); 
                } else {
                        // Anzahl wg. Performance beschränken
                        if ($this->widgetConfiguration['maid'] != "") {
                            // Nichts beschränken da Mitarbeiter ausgewählt
                            $key = 'alle';
                            $this->view->assign('key', $key);
                        } else {
                            $limit = (int) ($this->settings['elemente']['max']) ?: NULL;
                            if ($limit < 1 || $limit == NULL) {
                                    $limit = 50;
                            }
                            $query->setLimit($limit);
                        }                        
                } 

                $modifiedObjects = $query->execute();		

                $this->view->assign('contentArguments', array(
                        $this->widgetConfiguration['as'] => $modifiedObjects                    
                ));

        }
        
        // Erstelle ein Array mit allen Buchstaben von A bis Z
        foreach (range('A', 'Z') as $letter) {
            $letters[] = $letter;
        }
	
        $this->view->assign('letters', $letters);
        $this->view->assign('char', $char);        
    }
}
?>