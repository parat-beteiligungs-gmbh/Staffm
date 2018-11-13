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

namespace Pmwebdesign\Staffm\Domain\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use Pmwebdesign\Staffm\Utility\LanguageUtility;

/**
 * Description of CacheService
 *
 * @author Markus Puffer <m.puffer@pm-webdesign.eu>
 */
class CacheService
{
    /**
     * Caching Framework
     *
     * @var \TYPO3\CMS\Core\Cache\CacheManager     
     */
    protected $cache;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cache = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager')->getCache('staffm_mycache');
    }

    /**
     * Delete Caches of the given object
     * 
     * @param string $designation
     * @param string $actionname Action name
     * @param string $identifier Controller name
     * @param integer $uid
     */
    public function deleteCaches($designation, $actionname, $identifier, $uid)
    {        
        // Get first char
        $char = $this->getFirstChar($designation);
        // For all languages
        for ($i = 0; $i <= LanguageUtility::getLastLanguageIndex(); $i++) {
            if($actionname != "show") {                            
                $this->cache->remove($i.$actionname.$identifier);
                $this->cache->remove($i.$actionname.$identifier."All");
                $this->cache->remove($i.$actionname.$identifier.$char);
                $this->cache->remove($i.$actionname.$identifier."Adm");
                $this->cache->remove($i.$actionname.$identifier."AllAdm");
                $this->cache->remove($i.$actionname.$identifier.$char."Adm");
            } else {
                $this->cache->remove($i.$actionname.$identifier.$uid);
            }
        }
    }

    /**
     * Return the Cache if exist
     * 
     * @param string $actionname Action name
     * @param string $identifier Controller name
     * @param string $char Clicked char
     * @param string $maid Id
     * @param integer $uid Show Uid
     * @return type
     */
    public function getCache($actionname, $identifier, $char, $maid, $uid)
    {
        /* Caching Framework */        
        $cachename = $this->getCacheName($actionname, $identifier, $char, $maid, $uid);
        if (($output = $this->cache->get($cachename)) !== false) {
            // Yes, return Cache
            return $output;
        }
        return NULL;
    }

    /**
     * Set the Cache
     * 
     * @param string $actionname Action name
     * @param string $identifier Controller name
     * @param \TYPO3\CMS\Extbase\Mvc\Controller\ActionController $output 
     * @param string $char Clicked char
     * @param string $maid Id
     * @param integer $uid Show Uid
     */
    public function setCache($actionname, $identifier, $output, $char, $maid, $uid)
    {
        $cachename = $this->getCacheName($actionname, $identifier, $char, $maid, $uid);             
        $this->cache->set($cachename, $output);        
    }    
    
    /**
     * Get the cache name
     * 
     * @param string $actionname Action name
     * @param string $identifier Controller name
     * @param string $char Clicked char
     * @param string $maid Id
     * @param integer $uid Show Uid
     * @return string
     */    
    protected function getCacheName($actionname, $identifier, $char, $maid, $uid)
    {       
        $cachename = LanguageUtility::getActuallyLanguageIndex().$actionname.$identifier;   
        
        // Char or employee id?
        if ($char != "" || $maid == "maid") {
            // All clicked?
            if ($char == '%') {              
                $char = "All";              
            // Char clicked?
            } elseif ($char <> '') {             
                // No, a other char is clicked
                $char = $char;
            // Employee id?
            } elseif ($maid == "maid") {
                // A employee id was send
                $char = "All";             
            }
            $cachename = $cachename.$char;            
        } 
        // Show Uid?
        if ($uid != 0) {            
            $cachename = $cachename.$uid;           
        } else {        
            $userService = GeneralUtility::makeInstance(UserService::class);        
            $admin = $userService->isAdmin();
            // User is admin?
            if ($admin == TRUE) {
                $cachename = $cachename."Adm";
            }
        }
        return $cachename;           
    }

    /**
     * Get the first Char and check if is from Hungary with double size
     * 
     * @param string $designation
     * @return string 
     */
    protected function getFirstChar($designation)
    {
        // If char is example 'Á'
        $char = strtoupper(substr($designation, 0, 2));
        if ($char == 'Á') {
            $char = 'A';
        } else if ($char == 'Ó') {
            $char = 'O';
        } else {
            $char = strtoupper(substr($designation, 0, 1));
        }
        return $char;
    }
}
