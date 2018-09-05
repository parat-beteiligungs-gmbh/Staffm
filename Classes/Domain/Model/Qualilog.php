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
 * Qualilog
 */
class Qualilog extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {
	/**
	 *
	 * @var \Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualifikation
	 */
	protected $qualifikation = NULL;

	/**
	 * bezeichnung
	 * 
	 * @var string
	 */
	protected $bezeichnung = '';
	
	/**
	 *
	 * @var string 
	 */
	protected $status = '';

	/**
	 *
	 * @var string 
	 * @validate Text, StringLength(minimum = 5, maximum = 255)
	 */
	protected $beschreibung = '';
	
	/**
	 *
	 * @var \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $bearbeiter 
	 */
	protected $bearbeiter = NULL;
	
	function getQualifikation() {
		return $this->qualifikation;
	}

	function setQualifikation(\Pmwebdesign\Staffm\Domain\Model\Qualifikation $qualifikation) {
		$this->qualifikation = $qualifikation;
	}
	
	function getBearbeiter() {
		return $this->bearbeiter;
	}

	function setBearbeiter(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $bearbeiter) {
		$this->bearbeiter = $bearbeiter;
	}
	
	function getBezeichnung() {
		return $this->bezeichnung;
	}

	function getBeschreibung() {
		return $this->beschreibung;
	}

	function setBezeichnung($bezeichnung) {
		$this->bezeichnung = $bezeichnung;
	}

	function setBeschreibung($beschreibung) {
		$this->beschreibung = $beschreibung;
	}
	
	function getStatus() {
		return $this->status;
	}

	function setStatus($status) {
		$this->status = $status;
	}
}