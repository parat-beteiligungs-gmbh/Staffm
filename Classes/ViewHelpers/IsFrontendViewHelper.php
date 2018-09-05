<?php
namespace Typovision\Simpleblog\ViewHelpers;

/** IsFrontendViewHelper
 * Prüft ob sich der User im Frontend, oder Backend befindet
 *
 * @author dvpm
 * @version 1.0
 */
class IsFrontendViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {
     public function render()
     {
         if (TYPO3_MODE == 'FE') {
             return $this->renderThenChild();
         } 
         return $this->renderElseChild();
     }
}
