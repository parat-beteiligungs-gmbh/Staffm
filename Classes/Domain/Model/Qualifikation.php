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
     * @validate String, StringLength(minimum = 5, maximum = 255)
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
            1 => "1: Befindet sich in der Anlernphase / In Sprachen kaum Kenntnisse / In Ausbildung",
            2 => "2: Kann die Tätigkeit in der geforderten Zeit und Qualität / Grundkenntnisse. Werden z.B. durch Teilnahme bei Arbeitsgruppen erreicht /In Sprachen vergleichbar Niveaustufe A / abgeschlossene Ausbildung",
            3 => "3: Ist in der Lage andere einzuarbeiten / Erweiterte Kenntnisse. Kann die bestehenden und für den jeweiligen Bereich zutreffenden Systeme bzw. Abläufe in der täglichen Praxis anwenden. Diese Stufe lässt sich durch interne o. externe Schulungen oder mind. 3-jährige Erfahrung erreichen / In Sprachen vergleichbar Niveaustufe B / Zusatzqualifikationen (z.B. CAD)",
            4 => "4: Kann selbst Optimierungslösungen finden und deren Umsetzung einleiten / Umfangreiche Kenntnisse. Setzt vorhandene Systematik ein, kann Schwächen erkennen und Systeme weiterentwickeln. Diese Stufe lässt sich nur durch eine nachgewiesene externe Schulung und Praxiserfahrung oder Berufsausbildung erreichen / In Sprachen vergleichbar Niveaustufe C / Fachwirt, Meister, Betriebswirt, Bachelor, Master, Ingenieur"
        ];
    }
}
