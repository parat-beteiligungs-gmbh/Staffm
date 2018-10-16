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

namespace Typovision\Simpleblog\ViewHelpers;

/**
 * Has Error Viewhelper
 *
 * @author dvpm
 */
class HasErrorViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Form\AbstractFormViewHelper
{
    public function initializeArguments()
    {
        $this->registerArgument('property', 'string', 'Name of object property.', TRUE);
    }
    
    /**
     * @param string $then
     * @param string $else
     * @return string
     */
    public function render($then = '', $else = '')
    {
        $originalRequestMappingResults = $this->controllerContext->getRequest()->getOriginalRequestMappingResults();
        $formObjectName = $this->viewHelperVariableContainer->get(
                'TYPO3\\CMS\\Fluid\\ViewHelpers\\FormViewHelper', 'formObjectName');
        $errors = $originalRequestMappingResults->forProperty($formObjectName)->forProperty($this->arguments['property']);
        if($errors->hasErrors() == 1) {
            return $then;
        } else {
            return $else;
        }
    }
}
?>