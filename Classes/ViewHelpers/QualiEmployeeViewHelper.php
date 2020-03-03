<?php

/*
 * Copyright (C) 2019 pm-webdesign.eu 
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
 * Check qualification of employee
 */
class QualiEmployeeViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('employee', \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter::class, '', true, null);
        $this->registerArgument('qualification', \Pmwebdesign\Staffm\Domain\Model\Qualifikation::class, '', true, null);
    }
    
    /**
     * @return \Pmwebdesign\Staffm\Domain\Model\Employeequalification
     */
    public function render()
    {        
        $employeequalification = NULL;
        
        $employee = $this->arguments['employee'];
        $qualification = $this->arguments['qualification'];
        if ($employee->getEmployeequalifications() != NULL) {            
            // All employee qualifications
            foreach ($employee->getEmployeequalifications() as $eq) {
                // Qualification found in employeequalification of employee?
                if ($eq->getQualification() === $qualification) {
                    $employeequalification = $eq;
                    break;
                }
            }
        } 
        return $employeequalification;
    }

}