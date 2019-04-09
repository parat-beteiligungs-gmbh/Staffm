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
 * TODO: Check qualification of employee
 */
class QualiEmployeeViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $employee
     * @param \Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualification
     * @return \Pmwebdesign\Staffm\Domain\Model\Employeequalification
     */
    public function render(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $employee = NULL, \Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualification = null)
    {
        $employeequalification = NULL;
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