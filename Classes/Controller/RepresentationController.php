<?php

/*
 * Copyright (C) 2020 PARAT Beteiligungs GmbH
 * Markus Puffer (mpuffer@parat.eu)
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

use \Pmwebdesign\Staffm\Domain\Repository\RepresentationRepository;

/**
 * Representation Controller
 *
 * @author Markus Puffer (mpuffer@parat.eu)
 */
class RepresentationController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * Repository for represenations
     *
     * @var RepresentationRepository
     */
    protected $representationRepository = null;
    
    /**
     * Inject repository for representations
     * 
     * @param RepresentationRepository $representationRepository
     */
    public function injectRepresentationRepository(RepresentationRepository $representationRepository) {
        $this->representationRepository = $representationRepository;
    }
        
    /**
     * Set deputy status
     */
    public function setDeputyActiveStatusAction()
    {
        $representationUid = $this->request->getArgument('representationUid');        
        $cb = $this->request->getArgument('cbStatus');
        if($cb == "true") {
            $cb = 1;
        } else {
            $cb = 0;
        }
        
        /* @var $representation \Pmwebdesign\Staffm\Domain\Model\Representation */
        $representation = $this->representationRepository->findByUid($representationUid);
        $representation->setStatusActive($cb);
        $this->representationRepository->update($representation);   
        
        return "Deputy Status: " . $cb;
    }    
    
    /**
     * Set deputy status
     */
    public function setQualificationAuthorizationStatusAction()
    {
        $representationUid = $this->request->getArgument('representationUid');        
        $cb = $this->request->getArgument('cbStatus');
        if($cb == "true") {
            $cb = 1;
        } else {
            $cb = 0;
        }
        
        /* @var $representation \Pmwebdesign\Staffm\Domain\Model\Representation */
        $representation = $this->representationRepository->findByUid($representationUid);
        $representation->setQualificationAuthorization($cb);
        $this->representationRepository->update($representation);   
        
        return "Deputy Qualification Status: " . $cb;
    }    
}
            
            
     