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

namespace Pmwebdesign\Staffm\Service;

/**
 * Caching
 */
class Caching {
    const DEFAULT_CACHE_EXTENSIONKEY = 'staffm_mycache';
    /**
     * @var \TYPO3\CMS\Core\Cache\Frontend\AbstractFrontend
     */
    protected $cacheInstance;
    /**
     * @var string
     */
    protected $extensionName;
    /**
     * Generate unique cache identifier from string
     *
     * @param string $token
     * @return string
     */
    public function calculateCacheIdentifierWithRandom($token) {
        return sha1($token . rand());
    }
    /**
     * Initialize caching instance
     * Beware, if you're not using the standard configuration, you need to implement a new clearCacheHook
     *
     * @param string $extensionName
     */
    public function initializeCache($extensionName) {
        $this->extensionName = $extensionName;
        if(!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$extensionName]))
            $extensionName = self::DEFAULT_CACHE_EXTENSIONKEY;            

        try {
            $this->cacheInstance = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager')->getCache($extensionName);
        } catch (\TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException $e) {
            $this->cacheInstance = $GLOBALS['typo3CacheFactory']->create(
                $extensionName,
                $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$extensionName]['frontend'],
                $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$extensionName]['backend'],
                $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$extensionName]['options']
            );
        }
    }
    /**
     * Store the value in the cache
     *
     * @param string $cacheIdentifier
     * @param string $entry
     * @param array  $tags
     * @param int    $lifetime
     */
    public function set($cacheIdentifier, $entry, array $tags = null, $lifetime = 3600) {
        $this->cacheInstance->set($this->extensionName.$cacheIdentifier, $entry, $tags, $lifetime);
    }
    /**
     * Return the cached value or FALSE if it doesn't exist
     *
     * @param string $cacheIdentifier
     *
     * @return string|false
     */
    public function get($cacheIdentifier) {
        return $this->cacheInstance->get($this->extensionName.$cacheIdentifier);
    }
    /**
     * Delete entry with $cacheIdentifier
     *
     * @param $cacheIdentifier
     */
    public function remove($cacheIdentifier) {
        $this->cacheInstance->remove($this->extensionName.$cacheIdentifier);
    }
    /**
     * Clear the entire cache
     */
    public function flush() {
        $this->cacheInstance->flush();
    }
    /**
     * Clear the cache by a tag
     *
     * @param string $tag
     */
    public function flushByTag($tag) {
        $this->cacheInstance->flushByTag($tag);
    }
}