<?php
namespace Pmwebdesign\Staffm\ViewHelpers\Widget\Controller;

/** SortController
 * Controller für die Sortierung
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