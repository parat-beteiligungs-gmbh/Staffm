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

namespace Typovision\Simpleblog\ViewHelpers;

/**
 * Gravatar Viewhelper
 *
 * @author dvpm
 */
class GravatarViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper
{
    protected $tagName = 'img';
    
    public function initializeArguments() {
        $this->registerArgument('email', 'string', 'Email for lookup at gravatar database', FALSE);
        $this->registerArgument('size', 'integer', 'Size of gravatar picture', FALSE, 100);
    }
    
    /**
     * @return string the HTML <img>-Tag of the gravatar
     */
    public function render() {
        $email = ($this->arguments['email'] !== NULL) ? $this->arguments['email'] : $this->renderChildren();
        $gravatarUri = 'http://www.gravatar.com/avatar/' . md5($email) . '?s=' . urlencode($this->arguments['size']);
        
        $this->tag->addAttribute('src', $gravatarUri);
        return $this->tag->render();
    }    
}
?>