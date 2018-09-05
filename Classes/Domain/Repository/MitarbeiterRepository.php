<?php
namespace Pmwebdesign\Staffm\Domain\Repository;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2018 Markus Puffer <m.puffer@pm-webdesign.eu>, PM-Webdesign
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * The repository for Mitarbeiters
 */
class MitarbeiterRepository extends \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository {	
	protected $defaultOrderings = array('last_name' =>
        \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING);

        /**
	 * @param string $search
	 * @param int $limit
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface|Boolean
	 */
	public function findSearchForm($search, $limit) {                
		// Wenn im Suchfeld ein Wert
		if ($search != NULL) {
			// Falls mehrere Suchbegriffe eingegeben worden sind
			$searchArr = str_getcsv($search, " ");
			$query = $this->createQuery();
                        $quali = NULL;
			$quali = $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\QualifikationRepository')->findSearchForm($search, $limit);			
						
			// Qualifikationen ermitteln
			//$qMit = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();	
			$arrMit = [];	
                        
			// Qualifikationen prüfen			
                        if($quali != NULL) {
                            foreach($quali as $q) {
                                // Wenn Qualifikation gefunden in Array speichern
                                foreach ($q->getMitarbeiters() as $mq) {
                                    if ($mq != NULL) {
                                            array_push($arrMit, (int) $mq->getUid());							
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
                                //$constraints[++$i] = $query->equals('deleted', 0); //-> funktioniert nicht
                                
				// Wenn Qualifikationen gefunden -> TODO: Set in next row
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
		// Wenn kein Wert im Suchfeld
		} else {
			$query = $this->createQuery();
			$query->matching (
					$query->logicalAnd(
						$query->equals('deleted', 0)
						//$query->greaterThan('last_name', '')
						//$query->greaterThan('tx_igldapssoauth_dn', '')
					)
			);				
			//$limit = (int) $limit;
			/*$limit = 50;
			if ($limit > 0) {
				$query->setLimit($limit);
			}*/
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
	 * Mitarbeiter vom Vorgesetzten ermitteln
         *
         * @param string $search
         * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $vorgesetzter 
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findMitarbeiterVonVorgesetzten($search, \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $vorgesetzter = NULL) {
            // Kostenstellen des angemeldeten Users ermitteln               
            $kostenstellen = $this->objectManager->
            get('Pmwebdesign\\Staffm\\Domain\\Repository\\KostenstelleRepository')->
            findByVerantwortlicher($vorgesetzter);
            
            // Wenn im Suchfeld ein Wert
            if ($search != NULL) {
                    // Falls mehrere Suchbegriffe eingegeben worden sind
                    $searchArr = str_getcsv($search, " ");
                    $query = $this->createQuery();
                    $quali = NULL;
                    $quali = $this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\QualifikationRepository')->findSearchForm($search, $limit);
                   
                    // Qualifikationen ermitteln
                    $qMit = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();	
                    $arrMit = [];
                    
                    // Qualifikationen prüfen
                    if($quali != NULL) {
                        foreach($quali as $q) {
                            // Wenn Qualifikation gefunden in Array speichern
                            foreach ($q->getMitarbeiters() as $mq) {
                                if ($mq != NULL) {
                                        array_push($arrMit, (int) $mq->getUid());							
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
                            
                            // Wenn Qualifikationen gefunden
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
            // Wenn kein Wert im Suchfeld
            } else {
                    $query = $this->createQuery(); 
                    //$query->getQuerySettings()->setStoragePageIds(array(28)); // Setzt Seitenid
                    // Mehrere Kostenstellen?
                    if (count($kostenstellen) > 1) {                        
                        // Ja, mehrere Kostenstellen
                        $query->matching (
                            $query->logicalAnd(
                                    $query->equals('deleted', 0),
                                    $query->in('kostenstelle', $kostenstellen)
                                    //$query->greaterThan('tx_igldapssoauth_dn', '')
                            )
                        );	
                    } elseif (count($kostenstellen) == 1) {
                        // Ja, nur eine Kostenstelle
                        $query->matching (
                            $query->logicalAnd(
                                    $query->equals('deleted', 0),
                                    $query->equals('kostenstelle', $kostenstellen[0])
                                    //$query->greaterThan('tx_igldapssoauth_dn', '')
                            )
                        );	
                    } else {
                        // Nein, keine Kostenstelle
                        return null;
                    }                    
            }            
            
            $query->setOrderings(array('last_name' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));	                        
            $query->execute();
            //echo "Anzahl: ".$query->count();
            return $query->execute();
	}
}