<?php

/*
 * Copyright (C) 2021 PARAT Beteiligungs GmbH
 * Markus Blöchl <mbloechl@parat.eu>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Pmwebdesign\Staffm\Domain\Model;

/**
 * Description of Effect
 *
 * @author Markus Blöchl <mbloechl@parat.eu>
 */
class Effect extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity 
{
    /**
     * The name of the effect.
     * 
     * @var string
     */
    protected $name = '';
    
    /**
     * Setter for name
     * 
     * @param string $name
     */
    public function setName(string $name) 
    {
        $this->name = $name;
    }
    
    /**
     * Getter for name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
