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
 * Position
 */
class Position extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     *
     * @var string 
     */
    protected $nummer = "";

    /**
     * bezeichnung
     * 
     * @var string
     */
    protected $bezeichnung = '';

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
     * @param string $nummer
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
    function getBezeichnung()
    {
        return $this->bezeichnung;
    }

    /**
     * Sets the bezeichnung
     * 
     * @param string $bezeichnung
     * @return void
     */
    function setBezeichnung($bezeichnung)
    {
        $this->bezeichnung = $bezeichnung;
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

        foreach ($this->mitarbeiterRepository->findPositionMitarbeiter($this) as $mk) {
            $this->mitarbeiters->attach($mk);
        }

        return $this->mitarbeiters;
    }

}
