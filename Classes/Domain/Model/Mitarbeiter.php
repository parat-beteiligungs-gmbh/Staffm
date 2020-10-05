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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use Pmwebdesign\Staffm\Domain\Service\UserService;

/**
 * Employee
 * User from fe_users table of typo3 (frontend user)
 *  
 * @author Markus Puffer (m.puffer@pm-webdesign.eu)
 */
class Mitarbeiter extends \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
{
    /**
     * Pictures
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     * @lazy
     * @cascade remove
     */
    protected $image = null;
    
    /**
     * Employee number
     * 
     * @var string
     */
    protected $personalnummer = '';
    
    /**
     * View date of birth
     * 
     * @var integer
     */
    protected $dateOfBirthShow = 0;

    /**
     * Date of birth
     * 
     * @var integer
     */
    protected $dateOfBirth = 0;

    /**
     * Last Name
     * 
     * @var string
     */
    protected $lastName = '';

    /**
     * First Name
     * 
     * @var string
     */
    protected $firstName = '';

    /**
     * Position
     * 
     * @var \Pmwebdesign\Staffm\Domain\Model\Position
     * @lazy
     */
    protected $position = NULL;

    /**
     * Handy
     * 
     * @var string
     */
    protected $handy = '';

    /**
     * Cost center
     * 
     * @var \Pmwebdesign\Staffm\Domain\Model\Kostenstelle
     * @lazy
     */
    protected $kostenstelle = NULL;

    /**
     * Company
     * 
     * @var \Pmwebdesign\Staffm\Domain\Model\Firma
     * @lazy
     */
    protected $firma = NULL;
        
    /**
     * Qualifications
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
     * Representations
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Representation>
     * @lazy   
     * @cascade remove
     */
    protected $representations = NULL;
    
    /**
     * Assigned Representations
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Representation>
     * @lazy  
     */
    protected $assignedRepresentations = NULL;
    
    /**
     * Is it a cost center responsible?
     *
     * @var bool
     */
    protected $isCostCenterResponsible = false;

    /**
     * objectManager
     * Required for picture upload
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager;

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
        $this->employeequalifications = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->categories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->representations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->assignedRepresentations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Get position
     * 
     * @return \Pmwebdesign\Staffm\Domain\Model\Position $position
     */
    function getPosition()
    {
        return $this->position;
    }

    /**
     * Set position
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Position $position
     */
    function setPosition(\Pmwebdesign\Staffm\Domain\Model\Position $position = NULL)
    {
        $this->position = $position;
    }
    
    /**
     * Return the images
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage $images
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Sets the images
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $images
     * @return void
     */
    public function setImage(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $image)
    {
        $this->image = $image;
    }

    /**
     * Adds a FileReference
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
     * @return void
     */
    public function addImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $image)
    {
        $this->image->attach($image);
    }

    /**
     * Removes a FileReference
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $image The FileReference to be removed
     * @return void
     */
    public function removeImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $image)
    {
        $this->image->detach($image);
    }

    /**
     * Returns the personalnummer
     * 
     * @return string $personalnummer
     */
    public function getPersonalnummer()
    {
        return $this->personalnummer;
    }

    /**
     * Sets the personalnummer
     * 
     * @param string $personalnummer
     * @return void
     */
    public function setPersonalnummer($personalnummer)
    {
        $this->personalnummer = $personalnummer;
    }
    
    /**
     * Returns the marked DateOfBirth
     * 
     * @return integer $dateOfBirthShow
     */
    function getDateOfBirthShow()
    {
        return $this->dateOfBirthShow;
    }

    /**
     * 
     * @param integer $dateOfBirthShow
     */
    function setDateOfBirthShow($dateOfBirthShow)
    {
        $this->dateOfBirthShow = $dateOfBirthShow;
    }

    
    /**
     * Returns the dateOfBirth
     * 
     * @return integer $dateOfBirth
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Sets the dateOfBirth
     * 
     * @param integer $dateOfBirth
     * @return void
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * Returns the handy
     * 
     * @return string $handy
     */
    public function getHandy()
    {
        return $this->handy;
    }

    /**
     * Sets the handy
     * 
     * @param string $handy
     * @return void
     */
    public function setHandy($handy)
    {
        $this->handy = $handy;
    }

    /**
     * Returns the kostenstelle
     * 
     * @return \Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle
     */
    public function getKostenstelle()
    {
        return $this->kostenstelle;
    }

    /**
     * Sets the kostenstelle
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle
     * @return void
     */
    public function setKostenstelle(\Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle = NULL)
    {       
        $this->kostenstelle = $kostenstelle;
    }

    /**
     * Returns the firma
     * 
     * @return \Pmwebdesign\Staffm\Domain\Model\Firma $firma
     */
    public function getFirma()
    {
        return $this->firma;
    }

    /**
     * Sets the firma
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Firma $firma
     * @return void
     */
    public function setFirma(\Pmwebdesign\Staffm\Domain\Model\Firma $firma = NULL)
    {
        $this->firma = $firma;
    }
   
    /**
     * Get the Qualifications
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Employeequalification>
     */
    public function getEmployeequalifications()
    {
        return $this->employeequalifications;
    }

    /**
     * Set the Qualifications
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Employeequalification> $employeequalifications
     */
    public function setEmployeequalifications(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $employeequalifications)
    {
        $this->employeequalifications = $employeequalifications;
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
     * Get representations
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Representation>
     */
    public function getRepresentations()
    {
        return $this->representations;
    }

    /**
     * Set representations
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Representation> $representations
     */
    public function setRepresentations(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $representations)
    {
        $this->representations = $representations;
    }
    
    /**
     * Get assigned representations
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Representation>
     */
    public function getAssignedRepresentations()
    {
        return $this->assignedRepresentations;
    }
    
    /**
     * Set assigned representations
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Representation> $representations
     */
    public function setAssignedRepresentations(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $assignedRepresentations)
    {
        $this->assignedRepresentations = $assignedRepresentations;
    }
    
    /**
     * Is it a cost center responsible?
     * 
     * @return bool
     */
    public function getIsCostCenterResponsible() : bool
    {        
        /* @var $userService UserService */
        $userService = GeneralUtility::makeInstance(UserService::class);
        return $userService->isCostCenterResponsible($this);
    }    
}
