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

/**
 * Category Services
 *
 * @author Markus Puffer <m.puffer@pm-webdesign.eu>
 */
class CategoryService
{
    /**
     * Check assigned categories
     * 
     * @param \TYPO3\CMS\Extbase\Mvc\Request $request
     * @param \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager  
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pmwebdesign\Staffm\Domain\Model\Category>
     */
    public function getCategories(\TYPO3\CMS\Extbase\Mvc\Request $request, \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager)
    {
        if ($request->hasArgument('categories')) {           
            $categories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();

            // Read checkboxes into array
            $cat = $request->getArgument('categories');

            // Set categories to array items
            foreach ($cat as $c) {
                /* @var $category \Pmwebdesign\Staffm\Domain\Model\Category */
                $category = $objectManager->get(
                                'Pmwebdesign\\Staffm\\Domain\\Repository\\CategoryRepository'
                        )->findOneByUid($c);                
                $categories->attach($category);
            }
            return $categories;
        }
    }
}
