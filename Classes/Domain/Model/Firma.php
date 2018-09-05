<?php

namespace Pmwebdesign\Staffm\Domain\Model;

/* * *************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2018 Markus Puffer <m.puffer@pm-webdesign.eu>, PM-Webdesign
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 * Company
 */
class Firma extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Number
     *
     * @var string 
     */
    protected  $nummer = "";

    /**
     * Designation
     * 
     * @var string
     */
    protected $bezeichnung = '';
    
    /**
     * Address
     *
     * @var string
     */
    protected $adresse = "";
    
    /**
     * Zip code
     *
     * @var string
     */
    protected $plz = "";
           
    /**
     * Place
     *
     * @var string
     */
    protected $ort = "";
    
    /**
     * Additional place information
     *
     * @var string
     */
    protected $ortszusatz = "";
    
    /**
     * Country
     *
     * @var string
     */
    protected $land = "";
    
    /**
     * Telephone number 1
     *
     * @var string
     */
    protected $tel1 = "";
    
    /**
     * Telephone number 2
     *
     * @var string
     */
    protected $tel2 = "";
    
    /**
     * Fax number
     *
     * @var string
     */
    protected $fax = "";
    
    /**
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     * @cascade remove
     */
    protected $images = null;
    
    /**
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     * @cascade remove 
     */
    protected $files = null;

    /**
     * mitarbeiterRepository
     * 
     * @var \Pmwebdesign\Staffm\Domain\Repository\MitarbeiterRepository
     * @inject
     */
    protected $mitarbeiterRepository = NULL;

    /**
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter>  
     * @lazy
     */
    protected $mitarbeiters = NULL;
    
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
        $this->files = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Return the number
     * 
     * @return string $nummer
     */
    function getNummer()
    {
        return $this->nummer;
    }

    /**
     * Set the number
     * 
     * @param type $nummer
     * @return void 
     */
    function setNummer($nummer)
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
     * Return the employees
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter> $mitarbeiters
     */
    function getMitarbeiters()
    {
        if ($this->mitarbeiters == null) {
            $this->mitarbeiters = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        }

        foreach ($this->mitarbeiterRepository->findFirmaMitarbeiter($this) as $mk) {
            $this->mitarbeiters->attach($mk);
        }

        return $this->mitarbeiters;
    }
    
    /**
     * Set the employees
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter> $mitarbeiters
     */
    function setMitarbeiters(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $mitarbeiters)
    {
        $this->mitarbeiters = $mitarbeiters;
    }
    
    /**
     * Get the address
     * 
     * @return string
     */
    function getAdresse()
    {
        return $this->adresse;
    }
    
    /**
     * Get the Zip Code
     * 
     * @return string
     */
    function getPlz()
    {
        return $this->plz;
    }

    /**
     * Get the Place
     * 
     * @return string
     */
    function getOrt()
    {
        return $this->ort;
    }

    /**
     * Get the additional information about place
     * 
     * @return string
     */
    function getOrtszusatz()
    {
        return $this->ortszusatz;
    }

    /**
     * Get the country
     * 
     * @return string
     */
    function getLand()
    {
        return $this->land;
    }

    /**
     * Get the telephone number 1
     * 
     * @return string
     */
    function getTel1()
    {
        return $this->tel1;
    }

    /**
     * Get the telephone number 2
     * 
     * @return string
     */
    function getTel2()
    {
        return $this->tel2;
    }

    /**
     * Get the Fax number
     * 
     * @return string
     */
    function getFax()
    {
        return $this->fax;
    }

    /**
     * Return the images
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $images
     */
    function getImages()
    {
        return $this->images;
    }
    
    /**
     * Return the files
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $files
     */
    function getFiles()
    {
        return $this->files;
    }

    /**
     * Set the address
     * 
     * @param string $adresse
     */
    function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * Set the Zip Code
     * 
     * @param string $plz
     */
    function setPlz($plz)
    {
        $this->plz = $plz;
    }

    /**
     * Set the place
     * 
     * @param string $ort
     */
    function setOrt($ort)
    {
        $this->ort = $ort;
    }

    /**
     * Set the additional information for a place
     * 
     * @param string $ortszusatz
     */
    function setOrtszusatz($ortszusatz)
    {
        $this->ortszusatz = $ortszusatz;
    }
    
    /**
     * Set the Country
     * 
     * @param string $land
     */
    function setLand($land)
    {
        $this->land = $land;
    }

    /**
     * Set the Telephone number 1
     * 
     * @param string $tel1
     */
    function setTel1($tel1)
    {
        $this->tel1 = $tel1;
    }

    /**
     * Set the Telephone number 2
     * 
     * @param string $tel2
     */
    function setTel2($tel2)
    {
        $this->tel2 = $tel2;
    }

    /**
     * Set the Fax number
     * 
     * @param string $fax
     */
    function setFax($fax)
    {
        $this->fax = $fax;
    }

    /**
     * Sets the images
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $images
     */
    function setImages($images)
    {
        $this->images = $images;
    }
    
    /**
     * Sets the files
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $files
     */
    function setFiles($files)
    {
        $this->files = $files;
    }
}
