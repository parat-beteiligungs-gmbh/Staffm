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
class QualiViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper 
{
	/**
	 * 
	 * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $m
	 * @param \Pmwebdesign\Staffm\Domain\Model\Qualifikation $qu
	 * @return int 
	 */
	public function render(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $m = NULL, \Pmwebdesign\Staffm\Domain\Model\Qualifikation $qu = null) 
        {
            if($m != NULL) {
		$pruefe = 0;                
		foreach ($m->getQualifikationen() as $q) {                        
			if ($q === $qu) {
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
