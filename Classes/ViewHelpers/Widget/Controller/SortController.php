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

/** 
 * Controller for Sorting
 *
 * @author dvpm
 * @version 1.0
 */
class SortController extends \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController {
    /**
     *      
     */
    protected $objects;
    
    public function initializeAction() {
        $this->objects = $this->widgetConfiguration['objects']; // Um auf die Objekte vom ViewHelper zuzugreifen          
    }
    
    /**
     * @param string $order wird als Action-Link übergeben 
     * @param int $countmit Zähler für Elemente
     */
    public function indexAction($order =
            "Z", $countmit = 0) {
        // Prüfung auf welchen Wert $order gerade steht und dementsprechend geändert
        $order = ($order == 
                "Z") ?
                "A" :
                "Z";
				       
		if ($order == "Z") {
			$arrNeu = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
			// Array drehen
			for ($i = count($this->objects) -1; $i >= 0; $i--) {
				$arrNeu->attach($this->objects[$i]);
			}
			$modifiedObjects = $arrNeu;		
		} else {
			$modifiedObjects = $this->objects;
		}
		
		$countmit = count($modifiedObjects);		
        
        // Umsortierte Objekte über Bezeichner contentArguments an die View übergeben
        $this->view->assign('contentArguments', array(
            $this->widgetConfiguration['as'] => $modifiedObjects
        ));
        
        // Sortier-Reihenfolge als order
        $this->view->assign('order', $order);
        $this->view->assign('countmit', $countmit);
        //$this->view->assign('char', $char); // TODO: Test
    }
}
?>