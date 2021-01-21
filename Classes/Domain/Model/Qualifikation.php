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
 * Qualification
 */
class Qualifikation extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * Designation
     * 
     * @var string
     * @validate NotEmpty
     * @validate String, StringLength(minimum = 1, maximum = 50)
     */
    protected $bezeichnung = '';

    /**
     * Description
     *
     * @var string 
     * @validate NotEmpty
     * @validate String, StringLength(minimum = 5)
     */
    protected $beschreibung = '';

    /**
     * Qualification logs
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Qualilog>
     * @cascade remove
     * @lazy
     */
    protected $qualilogs = NULL;

    /**
     * Qualilog Repository
     * 
     * @var \Pmwebdesign\Staffm\Domain\Repository\QualilogRepository
     * @inject
     */
    protected $qualilogRepository = NULL;

    /**
     * Employees with this qualification
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Employeequalification>         
     * @lazy
     * @cascade remove
     */
    protected $employeequalifications = NULL;
    
    /**
     * Categories
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Category>
     * @lazy
     * @cascade remove
     */
    protected $categories = NULL;

    /**
     *
     * @var \Array
     */
    protected $status;
    
    /**
     *
     * @var \Array
     */
    protected $fullStatus;
    
    /**
     *
     * @var string
     */
    protected $stringStatus;

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
        $this->employeequalifications = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->categories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the bezeichnung
     * 
     * @return string $bezeichnung
     */
    public function getBezeichnung()
    {
        return $this->bezeichnung;
    }

    /**
     * Sets the bezeichnung
     * 
     * @param string $bezeichnung
     * @return void
     */
    public function setBezeichnung($bezeichnung)
    {
        $this->bezeichnung = $bezeichnung;
    }

    /**
     * 
     * @return string
     */
    public function getBeschreibung()
    {
        return $this->beschreibung;
    }

    /**
     * 
     * @param string $beschreibung
     */
    public function setBeschreibung($beschreibung)
    {
        $this->beschreibung = $beschreibung;
    }

    /**
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter> $employeequalifications
     */
    public function getEmployeequalifications()
    {
        return $this->employeequalifications;
    }

    /**
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter> $employeequalifications
     */
    public function setEmployeequalifications(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $employeequalifications)
    {
        $this->employeequalifications = $employeequalifications;
    }

    /**
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $employeequalifications
     */
    public function deleteEmployeequalifications()
    {
        $this->employeequalifications = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }
    
    /**      
     * Get categories
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Category>
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set categories
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Category> $categories
     */
    public function setCategories(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories)
    {
        $this->categories = $categories;
    }
    
    /**
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Qualilog> $qualilogs
     */
    public function getQualilogs()
    {
        if ($this->qualilogs == null) {
            $this->qualilogs = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        }

        foreach ($this->qualilogRepository->findQualifikationQualilog($this) as $q) {
            $this->qualilogs->attach($q);
        }

        return $this->qualilogs;
    }

    /**
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Qualilog> $qualilogs
     */
    public function setQualilogs(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $qualilogs)
    {
        $this->qualilogs = $qualilogs;
    }

    /**
     * 
     * @return \Array
     */
    public function getStatus()
    {
        return $this->status = [
            0 => "",
            1 => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('staffm.step', 'Staffm') . " 1",
            2 => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('staffm.step', 'Staffm') . " 2",
            3 => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('staffm.step', 'Staffm') . " 3",
            4 => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('staffm.step', 'Staffm') . " 4"
        ];
    }
    
    /**
     * 
     * @return \Array
     */
    public function getFullStatus()
    {
        return $this->fullStatus = [
            0 => "",
            1 => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('staffm.step1', 'Staffm'),
            2 => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('staffm.step2', 'Staffm'),
            3 => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('staffm.step3', 'Staffm'),
            4 => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('staffm.step4', 'Staffm'),
        ];
    }
    
    /**
     * 
     * @return \Array
     */
    public function getStringStatus()
    {
        $statusse = $this->getFullStatus();
        return $this->stringStatus = $statusse[1] . "\n\n" . $statusse[2] . "\n\n" . $statusse[3] . "\n\n" . $statusse[4];
    }
}
