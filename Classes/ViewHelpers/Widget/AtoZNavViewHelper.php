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

namespace Pmwebdesign\Staffm\ViewHelpers\Widget;

/** 
 * AtoZNavViewHelper
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

