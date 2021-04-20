<?php

/*
 * Copyright (C) 2021 PARAT Beteiligungs GmbH
 * Markus Blöchl <mbloechl@parat.eu>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Pmwebdesign\Staffm\Domain\Repository;

/**
 * Description of TrainingRepository
 *
 * @author Markus Blöchl <mbloechl@parat.eu>
 */
class TrainingRepository extends GlobalTrainingRepository
{
    /**
     * Searches for all trainings of the responsible.
     * 
     * @param int $responsibleUid
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult
     */
    public function findByResponsible($responsibleUid)
    {
        $query = $this->createQuery();
        $query->matching($query->equals('responsible', $responsibleUid));
        return $query->execute();
    }
}
