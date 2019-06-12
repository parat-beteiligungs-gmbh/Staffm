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

namespace Pmwebdesign\Staffm\Task;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use Pmwebdesign\Staffm\Domain\Repository\MitarbeiterRepository;
use Pmwebdesign\Staffm\Domain\Service\MailService;

/**
 * Description of SendMemories
 *
 * @author Markus Puffer <m.puffer@pm-webdesign.eu>
 */
class SendMemories extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{

    public function execute()
    {
        $objectManager = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);

        /* @var $employeeRepository MitarbeiterRepository */
        $employeeRepository = $objectManager->get(MitarbeiterRepository::class);

        /* @var $querySettings Typo3QuerySettings */
        $querySettings = $objectManager->get(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $querySettings->setIncludeDeleted(false);

        $employeeRepository->setDefaultQuerySettings($querySettings);
        $employees = $employeeRepository->findAll();

        /* @var $employee \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter */
        foreach ($employees as $employee) {
            // Has the employee qualifications?
            if ($employee->getEmployeequalifications()) {
                /* @var $employeeQualification \Pmwebdesign\Staffm\Domain\Model\Employeequalification */
                foreach ($employee->getEmployeequalifications() as $employeeQualification) {
                    if ($employeeQualification->getReminderDate() != NULL) {
                        $dateToday = new \DateTime('NOW');
                        $dateToday = $dateToday->format("Y-m-d");
                        $date = $employeeQualification->getReminderDate()->format("Y-m-d");
                        // Memory date the same as today?
                        if ($date == $dateToday) {
                            $countQualifications = $employeeQualification->getHistories()->count();
                            /* @var $history \Pmwebdesign\Staffm\Domain\Model\History */
                            foreach ($employeeQualification->getHistories() as $history) {//                                    
                                // Last History entry?
                                if ($employeeQualification->getHistories()->getPosition($history) == $countQualifications) {
                                    $message = "Hallo " . $history->getAssessor()->getFirstName() . ",\n\nhier ist eine Erinnerung (" . $employeeQualification->getReminderDate()->format("d.m.Y") . ") für den Mitarbeiter " .
                                            $employeeQualification->getEmployee()->getFirstName() . " " . $employeeQualification->getEmployee()->getLastName() . " " .
                                            "für die Qualifikation \"" . $employeeQualification->getQualification()->getBezeichnung() . "\"." .
                                            "\n\nFreundliche Grüße,\n\nPARAT Intranet";

                                    // Send a memory Email to the responsible
                                    /* @var $mailService MailService */
                                    $mailService = GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\MailService::class);

                                    $mailService->sendEmail("intranet@parat.eu", "PARAT Intranet", $history->getAssessor()->getEmail(), $history->getAssessor()->getFirstName() . " " . $history->getAssessor()->getLastName(), "Erinnerung für Qualifikation von Mitarbeiter " . $employeeQualification->getEmployee()->getFirstName() . " " . $employeeQualification->getEmployee()->getLastName(), $message);
                                }
                            }
                        }
                    }
                }
            }
        }
        return true;
    }
}
