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

namespace Pmwebdesign\Staffm\Domain\Repository;

/**
 * The repository for Cost Centers
 */
class KostenstelleRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * @param string $search
     * @param int $limit
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findSearchForm($search, $limit)
    {
        $query = $this->createQuery();
        
        if ($search != "") {
            $constraints = array();
            $searchArr = str_getcsv($search, " ");

            foreach ($searchArr as $s) {
                $constraints[] = $query->like('nummer', '%' . $s . '%');
                $constraints[] = $query->like('bezeichnung', '%' . $s . '%');
                $verant = $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->findSearchFormKst($s);
                foreach ($verant as $v) {
                    $constraints[] = $query->equals('verantwortlicher', $v);
                }
            }

            $query->matching(
                    $query->logicalOr(
                            $constraints
                    )
            );
        } 

        $query->setOrderings(array('bezeichnung' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
        $limits = (int) $limit;
        if ($limit > 0) {
            $query->setLimit($limits);
        }
        return $query->execute();
    }

    /**
     * Find Cost centers from a responsible/supervisor
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $employee
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findCostCentersFromResponsible(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $employee)
    {
        $query = $this->createQuery();
        $constraints = [];
        $constraints[] = $query->equals('verantwortlicher', $employee->getUid());
        // Representations
        /* @var $assignedRepresentation \Pmwebdesign\Staffm\Domain\Model\Representation */
//            foreach ($employee->getAssignedRepresentations() as $assignedRepresentation) {
//                $constraints[] = $query->equals('verantwortlicher', $assignedRepresentation->getEmployee()->getUid());
//                if($assignedRepresentation->getCostcenters() != NULL) {
//                    foreach ($assignedRepresentation->getCostcenters() as $costCenter) {
//                        $constraints[] = $query->logicalNot(
//                            $query->equals('uid', $costCenter->getUid())
//                            );
//                    }
//                }
//            }
        $query->matching(
                $query->logicalAnd(
                        $query->logicalOr(
                                $constraints
                        )
                )
        );
        $query->setOrderings(['nummer' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING]);
        return $query->execute();
    }

    /**
     * Find Cost centers from a responsible/supervisor
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $employee
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findCostCentersFromResponsibleAndDeputy(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $employee)
    {
        $query = $this->createQuery();
        $constraints = [];
        $constraints[] = $query->equals('verantwortlicher', $employee->getUid());
        // Representations
        /* @var $assignedRepresentation \Pmwebdesign\Staffm\Domain\Model\Representation */
        foreach ($employee->getAssignedRepresentations() as $assignedRepresentation) {
            $constraints[] = $query->equals('verantwortlicher', $assignedRepresentation->getEmployee()->getUid());
            if ($assignedRepresentation->getCostcenters() != NULL) {
                foreach ($assignedRepresentation->getCostcenters() as $costCenter) {
                    $constraints[] = $query->logicalNot(
                            $query->equals('uid', $costCenter->getUid())
                    );
                }
            }
        }
        $query->matching(
                $query->logicalAnd(
                        $query->logicalOr(
                                $constraints
                        )
                )
        );
        $query->setOrderings(['nummer' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING]);
        return $query->execute();
    }

}
