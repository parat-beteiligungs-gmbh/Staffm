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
        $this->registerArgument('extensionName', 'string', '', true, null);
        $this->registerArgument('sectionName', 'string', '', true, null);
        $this->registerArgument('constantName', 'string', '', true, null);
    }
    
    public function render() 
    {
        /*
         * extensionName is the extension key  + perhaps _pluginName
         * sectionName is the section where the constant is in the
         *             constants.typoscript is defined
         * constantName is the name of the constant
         */
        $extensionName = $this->arguments['extensionName'];
        $sectionName = $this->arguments['sectionName'];
        $constantName = $this->arguments['constantName'];
        
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
        
        $configurationManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager');
        $config = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $pageId = $config['plugin.']['tx_' . $extensionName . '.'][$sectionName . '.'][$constantName];
        return $pageId;
    }
}
