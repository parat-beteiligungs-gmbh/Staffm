<?php
namespace Pmwebdesign\Staffm\ViewHelpers;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 
 */
class QualiMitarbeiterViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper 
{
	/**
	 * 
         * @param \Pmwebdesign\Staffm\Domain\Model\Qualifikation $qu
	 * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $m	 
	 * @return int 
	 */
	public function render(\Pmwebdesign\Staffm\Domain\Model\Qualifikation $qu = NULL, \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $m = null) 
        {
            if($qu != NULL) {
		$pruefe = 0;                
		foreach ($qu->getMitarbeiters() as $ma) {                        
			if ($ma === $m) {
				$pruefe = 1;
			}			
		}
                
            } else {
		//$pruefe = 0;
		$pruefe = 0;
            }
            return $pruefe;
	}
}
