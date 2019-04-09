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

namespace Pmwebdesign\Staffm\Domain\Model;

/**
 * Employee qualification
 *
 * @author pm-webdesign.eu 
 * Markus Puffer <m.puffer@pm-webdesign.eu>
 */
class Employeequalification extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Qualification
     *
     * @var \Pmwebdesign\Staffm\Domain\Model\Qualifikation
     */
    protected $qualification = NULL;
    
    /**
     * Status of qualification
     *
     * @var integer
     */
    protected $status = 0;
    
    /**
     * Employee
     *
     * @var \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter
     */
    protected $employee = NULL;
    
    /**
     * Notice to qualification of employee
     *
     * @var string
     */
    protected $note = "";
    
    /**
     * Reminder date
     *
     * @var \DateTime
     */
    protected $reminderDate = NULL;
    
    /**
     * Histories of qualifications and status
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\History>
     * @lazy
     * @cascade remove
     */
    protected $histories = NULL;
    
    /**
     * Show the status
     *
     * @var boolean
     */
    protected $showstatus = FALSE;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->histories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }
    
    /**
     * Get the Qualification
     * 
     * @return \Pmwebdesign\Staffm\Domain\Model\Qualifikation
     */
    public function getQualification(): \Pmwebdesign\Staffm\Domain\Model\Qualifikation
    {
        return $this->qualification;
    }
    
    /**
     * Get the status
     * 
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get the employee
     * 
     * @return \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter
     */
    public function getEmployee(): \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter
    {
        return $this->employee;
    }

    /**
     * Get the notice
     * 
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }
    
    /**
     * 
     * @return \DateTime|NULL
     */
    public function getReminderDate()
    {
        return $this->reminderDate;
    }
    
    /**
     * Get the histories
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\History>
     */
    public function getHistories()
    {
        return $this->histories;
    }
    
    /**
     * Get the show status flag
     * 
     * @return boolean
     */
    public function getShowstatus()
    {
        return $this->showstatus;
    }
        
    /**
     * Set the qualification
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualification
     */
    public function setQualification(\Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualification)
    {
        $this->qualification = $qualification;
    }
    
    /**
     * Set the status
     * 
     * @param integer $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Set the employee
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $employee
     */
    public function setEmployee(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $employee)
    {
        $this->employee = $employee;
    }

    /**
     * Set the notice
     * 
     * @param string $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }    
    
    /**
     * 
     * @param $reminderDate
     */
    public function setReminderDate($reminderDate)
    {
        $this->reminderDate = $reminderDate;
    }
    
    /**
     * Set the histories
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\History> $histories
     */
    public function setHistories(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $histories)
    {
        $this->histories = $histories;
    }
    
    /**
     * Set the show status flag
     * 
     * @param boolean $showstatus
     */
    public function setShowstatus($showstatus)
    {
        $this->showstatus = $showstatus;
    }
}
