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
 * Description of Training
 *
 * @author Markus Blöchl <mbloechl@parat.eu>
 */
class Training extends Pmwebdesign\Staffm\Domain\Model\GlobalTraining 
{
    /**
     * The effect of the training
     * 
     * @var Effect
     */
    protected $effect = null;
    
    /**
     * The responible for the training.
     * (The superior of the members)
     * 
     * @var Mitarbeiter
     */
    protected $responsible = null;
    
    /**
     * Setter for effect
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Effect $effect
     */
    public function setEffect(Effect $effect) 
    {
        $this->effect = $effect;
    }
    
    /**
     * Getter for effect
     * 
     * @return Effect
     */
    public function getEffect()
    {
        return $this->effect;
    }
    
    /**
     * Setter for responsible
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $responsible
     */
    public function setResponsible(Mitarbeiter $responsible)
    {
        $this->responsible = $responsible;
    }
    
    /**
     * Getter for responsible
     * 
     * @return Mitarbeiter
     */
    public function getResponsible()
    {
        return $this->responsible;
    }
}
