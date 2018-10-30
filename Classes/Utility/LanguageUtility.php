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

namespace Pmwebdesign\Staffm\Utility;

/**
 * Language Utility
 *
 * @author Markus Puffer <m.puffer@pm-webdesign.eu>
 */
class LanguageUtility
{

    /**
     * Get the last language index
     * Example L(0-3) -> 3
     * 
     * @return integer
     */
    public static function getLastLanguageIndex()
    {
        return intval(substr($GLOBALS['TSFE']->pSetup['config.']['linkVars'], 4, 1));
    }

    /**
     * Get the actually language index
     * Example: 0 = German
     * 
     * @return integer
     */
    public static function getActuallyLanguageIndex()
    {
        return $GLOBALS['TSFE']->sys_language_uid;
    }

}
