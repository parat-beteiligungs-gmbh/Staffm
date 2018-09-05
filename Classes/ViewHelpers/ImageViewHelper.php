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
class ImageViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
	/** TODO: Testen weil Fehlermeldung im Backend
	 * 	
	 * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $m
	 * @return string 
	 */
	public function render(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $m = null) {
		$images = explode(",", $m->getImage());		
		return $images[0];
	}
}