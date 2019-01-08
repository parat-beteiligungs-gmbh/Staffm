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

namespace Pmwebdesign\Staffm\Domain\Repository;

use Pmwebdesign\Staffm\Utility\ArrayUtility;

/**
 * Employee Repository
 */
class MitarbeiterRepository extends \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository {	
	protected $defaultOrderings = array('last_name' =>
        \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING);
        
    /**
     * Qualification status who is ignored for normal users
     *
     * @var integer
     */
    protected $qualiStatusIgnore = 0;

    /**
     * @param string $search
     * @param int $limit
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface|Boolean
     */
    public function findSearchForm($search, $limit) {                
           // Search?
           if ($search != NULL) {
                   // If more search words
                   $searchArr = str_getcsv($search, " ");
                   $query = $this->createQuery();
                   $quali = NULL;
                   $quali = $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\QualifikationRepository')->findSearchForm($search, $limit);			

                   // Employee Array (Employee uids)
                   $arrMit = [];	
                   // Qualification?		
                   if($quali != NULL) {
                       /* @var $userService \Pmwebdesign\Staffm\Domain\Service\UserService */
                       $userService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Domain\Service\UserService::class);    
                       $user = $userService->getLoggedInUser();
                       if($user != NULL) {                                                            
                           $mitarbeiters = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
                           $mitarbeiters = ArrayUtility::fillOjectStorageFromQueryResult($this->findMitarbeiterVonVorgesetzten("", $user));     

                           /* @var $settingsUtility \Pmwebdesign\Staffm\Utility\SettingsUtility */
                           $settingsUtility = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Pmwebdesign\Staffm\Utility\SettingsUtility::class);
                           $this->qualiStatusIgnore = $settingsUtility->getQualiStatusIgnore();       
                       }
                       
                       foreach($quali as $q) {
                           // If qualification is found, save employee uid to array
                           foreach ($q->getEmployeequalifications() as $mq) {
                               // Logged in user and assigned employees?                                     
                               if ($user != NULL && $mitarbeiters != NULL) {                                        
                                   if (in_array($mq->getEmployee(), $mitarbeiters->toArray(), TRUE)) {
                                       array_push($arrMit, (int) $mq->getEmployee()->getUid());		
                                   // Employee qualification not null and status?  
                                   } elseif ($mq != NULL && $mq->getStatus() > $this->qualiStatusIgnore) {
                                       array_push($arrMit, (int) $mq->getEmployee()->getUid());	
                                   }
                               // Employee qualification not null and status?  
                               } elseif ($mq != NULL && $mq->getStatus() > $this->qualiStatusIgnore) {
                                   array_push($arrMit, (int) $mq->getEmployee()->getUid());	
                               }
                           }
                       }
                   }

                   $query = $this->createQuery();
                   $i = -1;
                   foreach ($searchArr as $key => $value) {
                           $constraints[++$i] = $query->like('last_name', '%'.$value.'%');
                           $constraints[++$i] = $query->like('first_name', '%'.$value.'%');
                           $constraints[++$i] = $query->like('username', '%'.$value.'%');
                           $constraints[++$i] = $query->like('personalnummer', '%'.$value.'%');
                           $constraints[++$i] = $query->like('title', '%'.$value.'%');
                           $constraints[++$i] = $query->like('telephone', '%'.$value.'%');
                           //$constraints[++$i] = $query->equals('deleted', 0); //-> doesn´t run

                           // Qualification?
                           if (count($arrMit) > 0) {
                                   $constraints[++$i] = $query->in('uid', $arrMit);					
                           }
                   }
                   $query->matching(					
                           $query->logicalAnd(					
                                   $query->logicalOr(
                                           $constraints
                                   )                                        
                           )   
                   );			
           // No Value in Search-Field
           } else {
                   $query = $this->createQuery();
                   $query->matching (
                                   $query->logicalAnd(
                                           $query->equals('deleted', 0)
                                           //$query->greaterThan('last_name', '')
                                           //$query->greaterThan('tx_igldapssoauth_dn', '')
                                   )
                   );		
           }

           $query->setOrderings(array('last_name' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING, 
               'first_name' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
           $limit = (int) $limit;
           if ($limit > 0) {
                   $query->setLimit($limit);
           }
           return $query->execute();
    }

    /**
     * 
     * 
     * @param string $search	
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findSearchFormKst($search) {
           $searchArr = str_getcsv($search, " ");
           $query = $this->createQuery();

           $i = -1;
           foreach ($searchArr as $key => $value) {
                   $constraints[++$i] = $query->like('last_name', '%'.$value.'%');
                   $constraints[++$i] = $query->like('first_name', '%'.$value.'%');
                   $constraints[++$i] = $query->like('username', '%'.$value.'%');
                   $constraints[++$i] = $query->like('personalnummer', '%'.$value.'%');
                   $constraints[++$i] = $query->like('title', '%'.$value.'%');
           }
           $query->matching(					
                           $query->logicalOr(
                                   $constraints
                           )
           );

           $query->setOrderings(array('last_name' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));		
           return $query->execute();
    }

    /**
     * Find employees from given position
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Position $position
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findPositionMitarbeiter(\Pmwebdesign\Staffm\Domain\Model\Position $position) {
           $query = $this->createQuery();
           $query->matching(	
                   $query->equals('position', $position)
           );
           $query->setOrderings(array('last_name' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));	
           return $query->execute();
    }

    /**
     * Find employees from given cost center
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findKostenstellenMitarbeiter(\Pmwebdesign\Staffm\Domain\Model\Kostenstelle $kostenstelle) {
           $query = $this->createQuery();
           $query->matching(	
                   $query->equals('kostenstelle', $kostenstelle)
           );
           $query->setOrderings(array('last_name' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));	
           return $query->execute();
    }

    /**
     * Find Employees from given company
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Firma $firma
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findFirmaMitarbeiter(\Pmwebdesign\Staffm\Domain\Model\Firma $firma) {
           $query = $this->createQuery();
           $query->matching(	
                   $query->equals('firma', $firma)
           );
           $query->setOrderings(array('last_name' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));	
           return $query->execute();
    }

    /**
     * Find employees from given place
     * 
     * @param \Pmwebdesign\Staffm\Domain\Model\Standort $standort
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findStandortMitarbeiter(\Pmwebdesign\Staffm\Domain\Model\Standort $standort) {
           $query = $this->createQuery();
           $query->matching(	
                   $query->equals('standort', $standort)
           );
           $query->setOrderings(array('last_name' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));	
           return $query->execute();
    }

    /**
     * TODO: Check if this is needed
     * 	
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findVorgesetzte() {
           $query = $this->createQuery();
           $query->matching(	
                   $query->equals('status', 27),
                   $query->equals('status', 51)
           );
           $query->setOrderings(array('last_name' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));	
           return $query->execute();
    }

    /**
     * Find employees of the supervisor or deputies of supervisor
     *
     * @param string $search
     * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $vorgesetzter 
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findMitarbeiterVonVorgesetzten($search, \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $vorgesetzter = NULL) {
       // Cost centers of logged in user
       $kostenstellen = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();

       $kostenstellen = ArrayUtility::fillOjectStorageFromQueryResult($this->objectManager->
           get('Pmwebdesign\\Staffm\\Domain\\Repository\\KostenstelleRepository')->
           findByVerantwortlicher($vorgesetzter));

       // Check deputy cost centers
       $representations = $this->objectManager->get(\Pmwebdesign\Staffm\Domain\Repository\RepresentationRepository::class)->findByDeputy($vorgesetzter);           
       foreach ($representations as $representation) {
           $costcentersSupervisor = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
           $costcentersSupervisor = ArrayUtility::fillOjectStorageFromQueryResult($this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\KostenstelleRepository')->findByVerantwortlicher($representation->getEmployee()));
           // Attach cost centers of supervisor
           foreach ($costcentersSupervisor as $costcenterSupervisor) {
               $kostenstellen->attach($costcenterSupervisor);
           }
           // Detach outsourced cost centers
           foreach ($representation->getCostcenters() as $costcenter) {
               $kostenstellen->detach($costcenter);
           }                
       }

       // Search field?
       if ($search != NULL) {
               // For more Search words
               $searchArr = str_getcsv($search, " ");
               $query = $this->createQuery();
               $quali = NULL;
               $quali = $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\QualifikationRepository')->findSearchForm($search, $limit);

               $qMit = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();	
               $arrMit = [];

               // Qualifications?
               if($quali != NULL) {
                   foreach($quali as $q) {
                       // Save qualifications in array
                       foreach ($q->getEmployeequalifications() as $mq) {
                           if ($mq != NULL) {
                                   array_push($arrMit, (int) $mq->getEmployee()->getUid());							
                           }
                       }
                   }
               }
               $query = $this->createQuery();
               $i = -1;
               foreach ($searchArr as $key => $value) {
                       $constraints[++$i] = $query->like('last_name', '%'.$value.'%');
                       $constraints[++$i] = $query->like('first_name', '%'.$value.'%');
                       $constraints[++$i] = $query->like('username', '%'.$value.'%');
                       $constraints[++$i] = $query->like('personalnummer', '%'.$value.'%');
                       $constraints[++$i] = $query->like('title', '%'.$value.'%');
                       $constraints[++$i] = $query->like('telephone', '%'.$value.'%');                                                        

                       // Qualifications?
                       if (count($arrMit) > 0) {
                               $constraints[++$i] = $query->in('uid', $arrMit);					
                       }
               }
               $query->matching(					
                       $query->logicalAnd(	
                               $query->in('kostenstelle', $kostenstellen),
                               $query->logicalOr(
                                       $constraints
                               )                                        
                       )

               );			
       // No search
       } else {
               $query = $this->createQuery(); 
               //$query->getQuerySettings()->setStoragePageIds(array(28)); // Setzt Seitenid
               // More cost centers?
               if (count($kostenstellen) > 1) {                        
                   // Yes, more cost centers
                   $query->matching (
                       $query->logicalAnd(
                               $query->equals('deleted', 0),
                               $query->in('kostenstelle', $kostenstellen)
                               //$query->greaterThan('tx_igldapssoauth_dn', '')
                       )
                   );	
               } elseif (count($kostenstellen) == 1) {
                   // One cost center
                   $query->matching (
                       $query->logicalAnd(
                               $query->equals('deleted', 0),
                               $query->equals('kostenstelle', $kostenstellen[0])
                               //$query->greaterThan('tx_igldapssoauth_dn', '')
                       )
                   );	
               } else {
                   // No cost center
                   return null;
               }                    
       }            

       $query->setOrderings(array('last_name' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));	                        
       $query->execute();      
       return $query->execute();
    }
}