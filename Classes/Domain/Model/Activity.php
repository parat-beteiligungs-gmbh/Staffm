<?php

/*
 * Copyright (C) 2019 pm-webdesign.eu 
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

namespace Pmwebdesign\Staffm\Domain\Model;

/**
 * Activity
 *
 * @author Markus Puffer <m.puffer@pm-webdesign.eu>
 */
class Activity extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Deadline
     *
     * @var \DateTime
     */
    protected $deadline = NULL;
    
    /**
     *Target deadline
     * 
     * @var \DateTime
     */
    protected $targetDeadline = NULL;
    
    /**
     * Art of certificate
     *
     * @var string
     */
    protected $certificateArt = "";
    
    /**
     * Notice
     *
     * @var string 
     */
    protected $note = "";
    
    /**
     * Attachements
     *
     * @var string 
     */
    protected $attachments = "";
            
    public function getDeadline(): \DateTime
    {
        return $this->deadline;
    }

    public function getTargetDeadline(): \DateTime
    {
        return $this->targetDeadline;
    }

    public function getCertificateArt()
    {
        return $this->certificateArt;
    }

    public function getNote()
    {
        return $this->note;
    }

    public function getAttachments()
    {
        return $this->attachments;
    }

    public function setDeadline(\DateTime $deadline)
    {
        $this->deadline = $deadline;
    }

    public function setTargetDeadline(\DateTime $targetDeadline)
    {
        $this->targetDeadline = $targetDeadline;
    }

    public function setCertificateArt($certificateArt)
    {
        $this->certificateArt = $certificateArt;
    }

    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * Set the attachments
     * 
     * @param \array $attachments
     */
    public function setAttachments($attachments)
    {
        if(!empty($attachments['name'])) {
            foreach ($attachments as $attachment) {
                // Name of file
                $attachmentName = $attachment['name'];
                // Temporary name (incl. path) from upload directory
                $attachmentTempName = $attachment['tmp_name'];
                /* @var $basicFileUtility \TYPO3\CMS\Core\Utility\File\BasicFileUtility */                
                //$basicFileUtility = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Utility\File\BasicFileUtility::class);
                
                /* @var $basicFileUtility \TYPO3\CMS\Core\DataHandling\DataHandler */
                $basicFileUtility = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\DataHandling\DataHandler::class);
                
                // Get unique filename (incl. path) TODO: Test
                $attachmentNameNew = $basicFileUtility->getUnique($attachmentName, \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('uploads/tx_staffm/files/'));
                // Set name without path
                $this->attachments += basename($attachmentNameNew).",";
                print_r($attachmentNameNew);
            }
            $this->attachments = substr($this->attachments, 0, -1);
        } else {
            if($attachments[0] == '') {
                $this->attachments = '';
            }
        }        
    }
}
