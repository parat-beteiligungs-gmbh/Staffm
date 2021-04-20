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

namespace Pmwebdesign\Staffm\Domain\Model;

/**
 * Description of GlobalTraining
 *
 * @author Markus Blöchl <mbloechl@parat.eu>
 */
class GlobalTraining extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity 
{
    /**
     * The name of the training
     * 
     * @var string
     */
    protected $name = "";
    
    /**
     * The time when the training is scheduled
     * 
     * @var \DateTime
     */
    protected $scheduledDate = null;
    
    /**
     * The time when the training was accomplished
     * 
     * @var \DateTime
     */
    protected $accomplishedDate = null;
    
    /**
     * The added notices
     * 
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Forms\Domain\Model\Notice>
     */
    protected $notices = null;
    
    /**
     * The history entries of the training.
     * 
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Forms\Domain\Model\History>
     */
    protected $histories = null;
    
    /**
     * All members of the training.
     * 
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter>
     */
    protected $members = null;
    
    /**
     * The number of the shifts of the training
     * 
     * @var int
     */
    protected $numberShifts = 0;
    
    /**
     * The qualifications that the training is assigned to.
     * 
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Qualifikation>
     */
    protected $assignedQualis = null;
    
    /**
     * Is the training canceled?
     * 
     * @var bool
     */
    protected $canceled = false;
    
    /**
     * Getter for name.
     * 
     * @return string
     */
    public function getName() 
    {
        return $this->name;
    }

    /**
     * Getter for scheduledDate
     * 
     * @return \DateTime
     */
    public function getScheduledDate() 
    {
        return $this->scheduledDate;
    }

    /**
     * Getter for accomplishedDate
     * 
     * @return \DateTime
     */
    public function getAccomplishedDate() 
    {
        return $this->accomplishedDate;
    }

    /**
     * Getter for notices
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getNotices() 
    {
        return $this->notices;
    }

    /**
     * Getter for histories
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getHistories() 
    {
        return $this->histories;
    }

    /**
     * Getter for members
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getMembers() 
    {
        return $this->members;
    }

    /**
     * Getter for numberShifts
     * 
     * @return int
     */
    public function getNumberShifts()
    {
        return $this->numberShifts;
    }

    /**
     * Getter for assignedQualis
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Qualifikation>
     */
    public function getAssignedQualis()
    {
        return $this->assignedQualis;
    }

    /**
     * Setter for name
     * 
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Setter for scheduledDate
     * 
     * @param \DateTime $scheduledDate
     */
    public function setScheduledDate(\DateTime $scheduledDate)
    {
        $this->scheduledDate = $scheduledDate;
    }

    /**
     * Setter for accomplishedDate
     * 
     * @param \DateTime $accomplishedDate
     */
    public function setAccomplishedDate(\DateTime $accomplishedDate)
    {
        $this->accomplishedDate = $accomplishedDate;
    }

    /**
     * Setter for notices
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $notices
     */
    public function setNotices($notices)
    {
        $this->notices = $notices;
    }

    /**
     * Setter for histories.
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $histories
     */
    public function setHistories($histories)
    {
        $this->histories = $histories;
    }

    /**
     * Setter for members
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $members
     */
    public function setMembers($members)
    {
        $this->members = $members;
    }

    /**
     * Setter for numberShifts
     * 
     * @param int $numberShifts
     */
    public function setNumberShifts(int $numberShifts)
    {
        $this->numberShifts = $numberShifts;
    }

    /**
     * Setter for assignedQualis
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Qualifikation> $assignedQualis
     */
    public function setAssignedQualis(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $assignedQualis)
    {
        $this->assignedQualis = $assignedQualis;
    }
    
    /**
     * Getter for canceled.
     * 
     * @return bool
     */
    public function getCanceled()
    {
        return $this->canceled;
    }
    
    /**
     * Setter for canceled
     * 
     * @param bool $canceled
     */
    public function setCanceled($canceled) {
        $this->canceled = $canceled;
    }
}