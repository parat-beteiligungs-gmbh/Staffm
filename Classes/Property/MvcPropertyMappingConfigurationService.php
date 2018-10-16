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

namespace Pmwebdesign\Staffm\Property;

use TYPO3\CMS\Extbase\Utility\ArrayUtility as ExtbaseArrayUtility;
use Pmwebdesign\Staffm\Utility\ArrayUtility as PmwebdesignArrayUtility;

/**
 * Class MvcPropertyMappingConfigurationService
 */
class MvcPropertyMappingConfigurationService extends \TYPO3\CMS\Extbase\Mvc\Controller\MvcPropertyMappingConfigurationService {
	/**
	 * Add support for variable argument lists using a wildcard property name '*'.
	 * This is required for a file multiupload, as you can't guess how many files
	 * will be uploaded when rendering the form (and generating the
	 * trustedPropertiesToken) on the server.
	 *
	 * You can use it like this:
	 * If you write a formfield viewhelper, you have to register all the properties
	 * that should be mapped when processing the input on the server. To allow
	 * the mapping of some properties of all submitted elements, insert a wildcard
	 * in the path at the position where new keys will appear. This class will
	 * enable the mapping of all arguments that are assigned to this path.
	 *
	 * So, if you have this line in your viewhelper:
	 *		$this->registerFieldNameForFormTokenGeneration('my_plugin[my_object][object_storage_property][*][foo]');
	 * and request arguments like this:
	 *		array( 'my_object' => array( 'object_storage_property' => array(
	 *			0 => array( 'foo' => 13 ),
	 *			1 => array( 'foo' => 42 ),
	 *			2 => array( 'foo' => false )
	 *		)))
	 * the PropertyMapper won't complain about missing permissions to "map
	 * attribute my_object.object_storage_property.0".
	 *
	 * This is different from simply using $propertyMappingConfiguration->allowAllProperties()
	 * because:
	 * - You don't have to post that line into each of your controllers
	 * - You can control which sub-properties to map
	 * - You don't override assigned settings for specific keys: if there is a
	 *   configuration for my_object.object_storage_property.42, it won't be
	 *   changed to the wildcard value.
	 *
	 * @param \TYPO3\CMS\Extbase\Mvc\Request $request
	 * @param \TYPO3\CMS\Extbase\Mvc\Controller\Arguments $controllerArguments
	 * @return void
	 */
	public function initializePropertyMappingConfigurationFromRequest(\TYPO3\CMS\Extbase\Mvc\Request $request, \TYPO3\CMS\Extbase\Mvc\Controller\Arguments $controllerArguments) {
		$trustedPropertiesToken = $request->getInternalArgument('__trustedProperties');
		if (!is_string($trustedPropertiesToken)) {
			return;
		}
		$serializedTrustedProperties = $this->hashService->validateAndStripHmac($trustedPropertiesToken);
		$trustedProperties = unserialize($serializedTrustedProperties);
		foreach ($trustedProperties as $propertyName => $propertyConfiguration) {
			if (!$controllerArguments->hasArgument($propertyName)) {
				continue;
			}
			$propertyMappingConfiguration = $controllerArguments->getArgument($propertyName)->getPropertyMappingConfiguration();
			//
			// Extended from parent class - begin
			//
			if (is_array($propertyConfiguration)) {
				foreach (PmwebdesignArrayUtility::getPathsToKey($propertyConfiguration, '*') as $path) {
					$configurationTemplate = ExtbaseArrayUtility::getValueByPath($propertyConfiguration, $path . '.*');
					$propertyConfiguration = ExtbaseArrayUtility::unsetValueByPath($propertyConfiguration, $path . '.*');
					if ($request->hasArgument($propertyName) && is_array($request->getArgument($propertyName))) {
						$rawArgument = ExtbaseArrayUtility::getValueByPath($request->getArgument($propertyName), $path);
						$subPropertyConfiguration = ExtbaseArrayUtility::getValueByPath($propertyConfiguration, $path);
						foreach ($rawArgument as $index => $_) {
							if (!is_int($index) || array_key_exists($index, $subPropertyConfiguration)) {
								continue;
							}
							$propertyConfiguration = ExtbaseArrayUtility::setValueByPath($propertyConfiguration, $path . '.' . $index, $configurationTemplate);
						}
					}
				}
			}
			//
			// Extended from parent class - end
			//
			$this->modifyPropertyMappingConfiguration($propertyConfiguration, $propertyMappingConfiguration);
		}
	}
}