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
class ArrayUtility
{

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
    static public function getPathsToKey(array $haystack, $needle, $includeNeedle = FALSE, $path = '')
    {
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

    /**
     * Verifies that images have been changed after a Controller action.
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $newImages
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $oldImages
     * @return boolean Return true if the images have changed
     */
    static public function checkImageCollections(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $newImages = NULL, \TYPO3\CMS\Extbase\Persistence\ObjectStorage $oldImages = NULL)
    {
        $state = FALSE;
        $i = 0;

        // Number has changed?
        if (count($newImages) != count($oldImages)) {
            $state = TRUE;
        } else {
            // New images
            foreach ($newImages as $newImage) {
                $i2 = 0;
                // Old images
                foreach ($oldImages as $oldImage) {
                    if ($i == $i2) {
                        if ($newImage->getUid() != $oldImage->getUid()) {
                            $state = TRUE;
                        }
                    }
                    $i2++;
                }
                $i++;
            }
        }
        return $state;
    }

    /**
     * Fill objectStorage from QueryResult
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\QueryResultInterface $queryResult
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    static public function fillOjectStorageFromQueryResult(\TYPO3\CMS\Extbase\Persistence\QueryResultInterface $queryResult = NULL)
    {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\Extbase\\Object\\ObjectManager');
        
        /* @var $objectStorage \TYPO3\CMS\Extbase\Persistence\ObjectStorage */
        $objectStorage = $objectManager->get('TYPO3\CMS\Extbase\Persistence\ObjectStorage');

        if (NULL !== $queryResult) {
            foreach ($queryResult AS $object) {
                $objectStorage->attach($object);
            }
        }
        return $objectStorage;
    }
    
    /**
     * Check if object is in ObjectStorage
     * 
     * @param type $object
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $objectStorage
     */
    static public function isObjectInObjectStorage($object, \TYPO3\CMS\Extbase\Persistence\ObjectStorage $objectStorage)
    {
        if(in_array($object, $objectStorage->toArray(), TRUE)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
