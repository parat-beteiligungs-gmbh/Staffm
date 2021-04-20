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
 * The repository for Qualifications
 */
class QualifikationRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    // Order
    protected $defaultOrderings = array(
        'bezeichnung' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
    );

    /**
     * @param string $search
     * @param int $limit
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface|Boolean
     */
    public function findSearchForm($search, $limit)
    {
        $query = $this->createQuery();
        if ($search != "") {
            $query->matching(
                    $query->logicalOr(
                            $query->like('bezeichnung', '%' . $search . '%'), $query->like('beschreibung', '%' . $search . '%')
                    )
            );
        }

        $query->setOrderings($this->defaultOrderings);
        $limits = (int) $limit;
        if ($limit > 0) {
            $query->setLimit($limits);
        }
        return $query->execute();
    }

    /**
     * Finds a Qualification by name.
     * 
     * @param string $name
     * @return \Pmwebdesign\Staffm\Domain\Model\Qualifikation
     */
    public function findByName($name) {
        $query = $this->createQuery();
        $query->matching($query->equals('bezeichnung', $name));
        return $query->execute()[0];
    } 
}
