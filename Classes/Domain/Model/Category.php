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
 * Category / Department
 *
 * @author Markus Puffer <m.puffer@pm-webdesign.eu>
 */
class Category extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Name
     *
     * @var string
     */
    protected $name = "";
    
    /**
     * Description
     *
     * @var string
     */
    protected $description = "";
    
    /**
     * Qualifications
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Qualifikation>
     * @lazy
     * @cascade remove
     */
    protected $qualifications = NULL;
    
    /**
     * Employees
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter>
     * @lazy
     * @cascade remove
     */
    protected $employees = NULL;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initStorageObjects();
    }

    /**
     * Initialize Storage objects
     * 
     * @return void 
     */
    protected function initStorageObjects()
    {   
        $this->qualifications = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();        
    }

    /**
     * Get name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     * 
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * Get description
     * 
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     * 
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
        
    /**
     * Get qualifications
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Qualifikation>
     */
    public function getQualifications()
    {
        return $this->qualifications;
    }

    /**
     * Set qualifications
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Qualifikation> $qualifications
     * @return void 
     */
    public function setQualifications(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $qualifications = NULL)
    {
        $this->qualifications = $qualifications;
    }
    
    /**
     * Get employees
     * 
     * @return @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter>
     */
    public function getEmployees()
    {
        return $this->employees;
    }

    /**
     * Set employees
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter> $employees
     * @return void
     */
    public function setEmployees(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $employees)
    {
        $this->employees = $employees;
    }
}
