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
 * Class containing more array helper functions.
 */
class ArrayUtility {
	/**
	 * Search $haystack recursive for keys $needle. Return an array that contains
	 * all paths to the key as dot-separated strings, as expected by
	 * TYPO3\CMS\Extbase\Utility\ArrayUtility::getValueByPath().
	 *
	 * @param array $haystack
	 * @param string $needle
	 * @param bool $includeNeedle
	 * @param string $path
	 * @return array
	 */
	static public function getPathsToKey(array $haystack, $needle, $includeNeedle = FALSE, $path = '') {
		$result = array();
		if (array_key_exists($needle, $haystack))
				$result[] = $path . ($includeNeedle ? '.' . $needle : '');
		if ($path !== '')
			$path .= '.';
		foreach ($haystack as $key => $value) {
			if (is_array($value))
				$result = array_merge($result, self::getPathsToKey($value, $needle, $includeNeedle, $path . $key));
		}
		return $result;
	}
}