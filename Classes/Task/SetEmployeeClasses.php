<?php

/*
 * Copyright (C) 2020 pm-webdesign.eu 
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

namespace Pmwebdesign\Staffm\Task;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use Pmwebdesign\Staffm\Domain\Repository\PositionRepository;
use Pmwebdesign\Staffm\Domain\Repository\MitarbeiterRepository;
use Pmwebdesign\Staffm\Domain\Service\MailService;

/**
 * Description of SendMemories
 *
 * @author Markus Puffer <m.puffer@pm-webdesign.eu>
 */
class SetEmployeeClasses extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{

    public function execute()
    {
        $objectManager = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);

        /* @var $querySettings Typo3QuerySettings */
        $querySettings = $objectManager->get(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $querySettings->setIncludeDeleted(false);

        /* @var $employeeRepository MitarbeiterRepository */
        $employeeRepository = $objectManager->get(MitarbeiterRepository::class);
        $employeeRepository->setDefaultQuerySettings($querySettings);
     
        /* Employees */
        $employees = $employeeRepository->findAll();
        
        /* Positions */
        /* @var $positionRepository PositionRepository */
//        $positionRepository = $objectManager->get(PositionRepository::class);
//        $positionRepository->setDefaultQuerySettings($querySettings);
        
        /* GF Positions */
//        $positionsGF = $positionRepository->findPositionGF();

        /* @var $employee \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter */
        foreach ($employees as $employee) {
            $extbaseType = "0";
            $update = true;
            if ($employee->getIsCostCenterResponsible() == true) {     
                $extbaseType = "1";
//                $employee->setTxExtbaseType('1'); // TODO-Error: Update doesn´t run! -> set in repository
            } elseif ($employee instanceof \BmParat\Adjustmentsheet\Domain\Model\ApplicationEngineer) {
                $update = false;
            }
            // Update?
            if ($update == true) {
                $employeeRepository->updateExtbaseType($employee, $extbaseType);
            }
        }
        $objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class)->persistAll();
       
        return true;
    }

}
