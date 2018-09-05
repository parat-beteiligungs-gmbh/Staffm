<?php
namespace Pmwebdesign\Staffm\Domain\Model;

/***************************************************************
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
 ***************************************************************/

/**
 * Qualifikation
 */
class Qualifikation extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * bezeichnung
	 * 
	 * @var string
	 * @validate NotEmpty
	 * @validate String, StringLength(minimum = 1, maximum = 50)
	 */
	protected $bezeichnung = '';
	
	/**
	 *
	 * @var string 
	 * @validate NotEmpty
	 * @validate String, StringLength(minimum = 5, maximum = 255)
	 */
	protected $beschreibung = '';
	
	/**
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Qualilog>
	 * @cascade remove
	 * @lazy
	 */
	protected $qualilogs = NULL;
	
	/**
	 * qualilogRepository
	 * 
	 * @var \Pmwebdesign\Staffm\Domain\Repository\QualilogRepository
	 * @inject
	 */
	protected $qualilogRepository = NULL;	
	
	/**
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter>         
         * @lazy
	 */
	protected $mitarbeiters = NULL;
        
	/**
	 * Returns the bezeichnung
	 * 
	 * @return string $bezeichnung
	 */
	public function getBezeichnung() {
		return $this->bezeichnung;
	}

	/**
	 * Sets the bezeichnung
	 * 
	 * @param string $bezeichnung
	 * @return void
	 */
	public function setBezeichnung($bezeichnung) {
		$this->bezeichnung = $bezeichnung;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getBeschreibung() {
		return $this->beschreibung;
	}
	
	/**
	 * 
	 * @param string $beschreibung
	 */
	public function setBeschreibung($beschreibung) {
		$this->beschreibung = $beschreibung;
	}
		
	/**
	 * 
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter> $mitarbeiters
	 */
	public function getMitarbeiters() {
//		if($this->mitarbeiters == null) {
//			$this->mitarbeiters = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
//		}
//		
//		foreach ($this->mitarbeiterqualifikationRepository->findQualifikationMitarbeiter($this) as $q) {
//			$this->mitarbeiters->attach($q->getMitarbeiter());
//		}	
		
		return $this->mitarbeiters;
	}
        
        /**
         * 
         * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter> $mitarbeiters
         */
        public function setMitarbeiters(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $mitarbeiters)
        {
            $this->mitarbeiters = $mitarbeiters;
        }
        
        /**
         * 
         * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $mitarbeiters
         */
        public function deleteMitarbeiters()
        {
            $this->mitarbeiters = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        }
        	
	/**
	 * 
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Qualilog> $qualilogs
	 */
	public function getQualilogs() {
		if($this->qualilogs == null) {
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
}