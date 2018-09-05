<?php
namespace Pmwebdesign\Staffm\ViewHelpers\Widget;

/**
 * Description of Widget
 *
 * @author dvpm
 * @version 1.0
 */
class SortViewHelper extends \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetViewHelper {    
    /** controller
     * Controller per Dependency Injection in die Klasse holen
     * @var \Pmwebdesign\Staffm\ViewHelpers\Widget\Controller\SortController
     * @inject
     */
    protected $controller;
    
    /**
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $objects übergebene Objekte
     * @param string $as Name des Ergebnis-Set
     * @param string $property Objekteigenschaft 
     * @return string
     */
    public function render($objects, $as, $property) {
        return $this->initiateSubRequest(); // SubRequest einleiten über den eingebundenen Controller und seine Action (indexAction())
    }
}
?>