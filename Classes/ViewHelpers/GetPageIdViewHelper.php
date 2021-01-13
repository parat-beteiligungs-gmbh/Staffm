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

namespace Pmwebdesign\Staffm\ViewHelpers;

/**
 * Description of GetPageIdViewHelper
 *
 * @author Markus Blöchl <mbloechl@parat.eu>
 */
class GetPageIdViewHelper  extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    public function initializeArguments()
    {
        $this->registerArgument('extension', 'string', '', true, null);
    }
    
    public function render() 
    {
        $extension = $this->arguments['extension'];
        
        $file = file_get_contents("typo3conf/ext/pageIds.txt");
        $sites = explode(";", $file);
        for($i = 0; $i < count($sites); $i++) {
            $entry = explode(":", $sites[$i]);
            if($entry[0] == $extension) {
                return $entry[1];
            }
        }
    }
}
