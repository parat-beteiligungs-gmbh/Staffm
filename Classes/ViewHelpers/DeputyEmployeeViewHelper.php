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

namespace Pmwebdesign\Staffm\ViewHelpers;

/**
 * Check Deputy of employee
 */
class DeputyEmployeeViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('c', \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter::class, '', true, null);
        $this->registerArgument('ma', \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter::class, '', true, null);
    }
    
    /**
     * Render
     * 
     * @return int 
     */
    public function render()
    {
        $c = $this->arguments['c'];
        $ma = $this->arguments['ma'];
        $pruefe = 0;
        $representation = null;
        if ($c != NULL) {           
            foreach ($c->getRepresentations() as $r) {                
                if ($r->getDeputy() === $ma) {
                    $pruefe = 1;
                    $representation = $r;
                    break;
                }
            }
        } 
        return $representation;
    }
}
