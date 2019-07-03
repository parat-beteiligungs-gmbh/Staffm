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

namespace Pmwebdesign\Staffm\Controller;

/**
 * Description of Activity
 *
 * @author Markus Puffer <m.puffer@pm-webdesign.eu>
 */
class ActivityController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * Activity Repository
     *
     * @var \Pmwebdesign\Staffm\Domain\Repository\ActivityRepository
     */
    protected $activityRepository = NULL;
    
    /**
     * Inject Activity Repository
     * 
     * @param \Pmwebdesign\Staffm\Domain\Repository\ActivityRepository $activityRepository
     */
    public function injectActivityRepository(\Pmwebdesign\Staffm\Domain\Repository\ActivityRepository $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }
    
    /**
     * Initialize
     * Action is called before other actions
     */
    public function initializeAction()
    {
        // Converts the file type 
        if($this->arguments->hasArgument('activity')) {
            $this->arguments->getArgument('activity')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('attachments', 'array');            
        }
    }

    /**
     * Create a new activity
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Activity $newActivity
     */
    public function createAction(\Pmwebdesign\Staffm\Domain\Model\Activity $newActivity)
    {
        $this->activityRepository->add($newActivity);
    }
    
    /**
     * Delete activity
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Activity $activity
     */
    public function deleteAction(\Pmwebdesign\Staffm\Domain\Model\Activity $activity)
    {
        $this->activityRepository->remove($activity);
    }
    
    public function deleteFileAction(\Pmwebdesign\Staffm\Domain\Model\Activity $activity, $attachement)
    {
        unlink($attachement); // Delete file
//        $arrAttachements = explode(",", $activity->g)
    }
}
