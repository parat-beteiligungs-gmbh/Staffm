<?php

namespace Pmwebdesign\Staffm\Property\FlexForm;

/*
 * Copyright (C) 2018 Markus Puffer (m.puffer@pm-webdesign.eu)
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

/**
 * FlexFormUserFunc
 * Various Functions for FlexForm in Backend
 *
 * @author Markus Puffer (m.puffer@pm-webdesign.eu)
 */
class FlexFormUserFunc
{
    /**
     * Get the Frontend User Groups
     * 
     * @param array $fConfig
     */
    public function getFrontendUserGroups(&$fConfig)
    {
        $gruppen = explode(",", $GLOBALS['TSFE']->gr_list);
        foreach ($gruppen as $group) {
            array_push($fConfig, $group);
        }
    }
    
    /**
     * Get the Frontend User Groups
     * 
     * @return array
     */
    public function getFrontendUserGroup()
    {
        $fConfig = array();
        $gruppen = explode(",", $GLOBALS['TSFE']->gr_list);
        foreach ($gruppen as $group) {
            array_push($fConfig, $group);
        }
        return $fConfig;
    }
}
