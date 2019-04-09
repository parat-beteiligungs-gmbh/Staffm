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

namespace Pmwebdesign\Staffm\Domain\Service;

/**
 * Mail Services
 *
 * @author Markus Puffer <m.puffer@pm-webdesign.eu>
 */
class MailService
{
   /**
    * Send one Email
    * 
    * @param string $fromEmail
    * @param string $fromName
    * @param string $toEmail
    * @param string $toName
    * @param string $subject
    * @param string $message
    */
    public function sendEmail(string $fromEmail, string $fromName, string $toEmail, string $toName, string $subject, string $message)
    {
        $mail = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Mail\MailMessage::class);
        $mail->setFrom(array($fromEmail => $fromName));
        $mail->setTo(array($toEmail => $toName));
        $mail->setSubject($subject);
        $mail->setBody($message);
        $mail->send();
    }
}
