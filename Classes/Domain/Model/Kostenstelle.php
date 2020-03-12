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
 * Cost Center
 * 
 * @author Markus Puffer (m.puffer@pm-webdesign.eu)
 */
class Kostenstelle extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * Cost center number
     * 
     * @var string
     */
    protected $nummer = '';

    /**
     * Designation
     * 
     * @var string
     */
    protected $bezeichnung = '';

    /**
     * Responsible of cost center
     * 
     * @var \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter
     */
    protected $verantwortlicher = NULL;

    /**
     * Employee repository
     * 
     * @var \Pmwebdesign\Staffm\Domain\Repository\MitarbeiterRepository
     * @inject
     */
    protected $mitarbeiterRepository = NULL;

    /**
     * Employees of cost center
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter>
     * @lazy
     */
    protected $mitarbeiters = NULL;

    /**
     * Images
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     * @cascade remove
     */
    protected $images = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initStorageObjects();      
    }

    /**
     * @return void 
     */
    protected function initStorageObjects()
    {
        $this->images = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the nummer
     * 
     * @return string $nummer
     */
    public function getNummer()
    {
        return $this->nummer;
    }

    /**
     * Sets the nummer
     * 
     * @param string $nummer
     * @return void
     */
    public function setNummer($nummer)
    {
        $this->nummer = $nummer;
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
     * Returns the verantwortlicher
     * 
     * @return \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $verantwortlicher
     */
    public function getVerantwortlicher()
    {
        return $this->verantwortlicher;
    }

    /**
     * Sets the verantwortlicher
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $verantwortlicher
     * @return void
     */
    public function setVerantwortlicher(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $verantwortlicher = NULL)
    {
        $this->verantwortlicher = $verantwortlicher;
    }

    /**
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter> $mitarbeiters
     */
    function getMitarbeiters()
    {
        if ($this->mitarbeiters == null) {
            $this->mitarbeiters = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        }

        foreach ($this->mitarbeiterRepository->findKostenstellenMitarbeiter($this) as $mk) {
            $this->mitarbeiters->attach($mk);
        }

        return $this->mitarbeiters;
    }

    /**
     * Return the images
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage $images
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Sets the images
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $images
     * @return void
     */
    public function setImages(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $images)
    {
        $this->images = $images;
    }
    
    /**
     * TODO: Add a image
     * 
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
     */
    public function addImages(\TYPO3\CMS\Extbase\Domain\Model\FileReference $image)
    {
        $this->images->attach($image);
    }

}
