<?php
namespace Pmwebdesign\Staffm\ViewHelpers\Widget;

/** AtoZNavViewHelper
 * Description of AtoZNavViewHelper
 *
 * @author dvpm
 * @version 1.0
 */
class AtoZNavViewHelper extends \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetViewHelper {
    /**
     * @var \Pmwebdesign\Staffm\ViewHelpers\Widget\Controller\AtoZNavController
     * @inject
     */
    protected $controller;
    
    /**
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $objects übergebene Objekte
     * @param string $as
     * @param string $property
     * @param string $search
     * @param string $maid   
     * @return string
     */
    public function render($objects, $as, $property, $search, $maid) {
        return $this->initiateSubRequest();
    }
}

