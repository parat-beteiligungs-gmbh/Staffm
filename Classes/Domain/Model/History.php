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
 * Description of History
 *
 * @author Markus Puffer <m.puffer@pm-webdesign.eu>
 */
class History extends \TYPO3\CMS\Extbase\DomainObject\AbstractValueObject
{
    /**
     * Status from qualification of the user
     *
     * @var integer
     */
    protected $status = 0;
    
    /**
     * Date from
     *
     * @var \DateTime
     */
    protected $dateFrom = NULL;
    
    /**
     * Date to
     *
     * @var \DateTime
     */
    protected $dateTo = NULL;   
    
    /**
     * Assessor of the employee
     *
     * @var \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter
     */
    protected $assessor = NULL;
    
    /**
     * 
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * 
     * @return \DateTime
     */
    public function getDateFrom(): \DateTime
    {
        return $this->dateFrom;
    }

    /**
     * 
     * @return \DateTime|NULL
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }
    
    /**
     * 
     * @return \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter
     */
    public function getAssessor(): \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter
    {
        return $this->assessor;
    }

    /**
     * 
     * @param integer $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * 
     * @param \DateTime $dateFrom
     */
    public function setDateFrom(\DateTime $dateFrom)
    {
        $this->dateFrom = $dateFrom;
    }

    /**
     * 
     * @param \DateTime $dateTo
     */
    public function setDateTo(\DateTime $dateTo)
    {
        $this->dateTo = $dateTo;
    }    

    /**
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $assessor
     */
    public function setAssessor(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $assessor)
    {
        $this->assessor = $assessor;
    }

}
