<?php
namespace Pmwebdesign\Staffm\Controller;

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
 * StandortController
 */
class StandortController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * standortRepository
	 * 
	 * @var \Pmwebdesign\Staffm\Domain\Repository\StandortRepository
	 * @inject
	 */
	protected $standortRepository = NULL;
	
	/**
	 * mitarbeiterRepository
	 * 
	 * @var \Pmwebdesign\Staffm\Domain\Repository\MitarbeiterRepository
	 * @inject
	 */
	protected $mitarbeiterRepository = NULL;
	
	/**
	 * action list
	 * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
	 * @return void
	 */
	public function listAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter = NULL) {	
		if ($this->request->hasArgument('search')) {
			$search = $this->request->getArgument('search');			
		} else {
			$search = NULL;			
		}
		$limit = 0;
		$standorts = $this->standortRepository->findSearchForm($search, $limit);
		// Überprüfen ob Argument gesetzt wurde
		if ($this->request->hasArgument('key')) {
			$key = $this->request->getArgument('key');
			$this->view->assign('key', $key);
		}		
		$this->view->assign('standorts', $standorts);
		$this->view->assign('mitarbeiter', $mitarbeiter);
		$this->view->assign('search', $search);
	}

	/**
	 * action show
	 * 
	 * @param \Pmwebdesign\Staffm\Domain\Model\Standort $standort
	 * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
	 * @return void
	 */
	public function showAction(\Pmwebdesign\Staffm\Domain\Model\Standort $standort = NULL,
			\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter = NULL) {
		if ($standort == NULL) {
			$key = $this->request->getArgument('key');			
			$this->view->assign('key', $key);		
			$standort = $mitarbeiter->getStandort();
			$this->view->assign('mitarbeiter', $mitarbeiter);		
		}
		$this->view->assign('standort', $standort);		
	}
	
	/**
	 * action choose
	 * 
	 * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter
	 * @param \Pmwebdesign\Staffm\Domain\Model\Standort $standort
	 * @return \Pmwebdesign\Staffm\Domain\Model\Standort
	 */
	public function chooseAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter, 
			\Pmwebdesign\Staffm\Domain\Model\Standort $standort) {	
		$mitarbeiter->setStandort($standort);
		$this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->update($mitarbeiter);
		$this->redirect('edit', 'Mitarbeiter', NULL, array('mitarbeiter' => $mitarbeiter));		
	}
	
	/**
	 * action deleteStandort
	 * Löscht den Standort eines Mitarbeiters
	 * 
	 * @param \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter	
	 */
	public function deleteStandortAction(\Pmwebdesign\Staffm\Domain\Model\Mitarbeiter $mitarbeiter) {
		$mitarbeiter->setStandort(NULL);
		$this->objectManager->get('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterRepository')->update($mitarbeiter);
		$this->redirect('edit', 'Mitarbeiter', NULL, array('mitarbeiter' => $mitarbeiter));			
	}

	/**
	 * action new
	 * 
	 * @param \Pmwebdesign\Staffm\Domain\Model\Standort $newStandort
	 * @ignorevalidation $newStandort
	 * @return void
	 */
	public function newAction(\Pmwebdesign\Staffm\Domain\Model\Standort $newStandort = NULL) {
		$this->view->assign('newStandort', $newStandort);
	}

	/**
	 * action create
	 * 
	 * @param \Pmwebdesign\Staffm\Domain\Model\Standort $newStandort
	 * @return void
	 */
	public function createAction(\Pmwebdesign\Staffm\Domain\Model\Standort $newStandort) {
		$this->addFlashMessage('Standort angelegt!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
		$this->standortRepository->add($newStandort);
		$this->redirect('list');
	}

	/**
	 * action edit
	 * 
	 * @param \Pmwebdesign\Staffm\Domain\Model\Standort $standort
	 * @ignorevalidation $standort
	 * @return void
	 */
	public function editAction(\Pmwebdesign\Staffm\Domain\Model\Standort $standort) {
		$this->view->assign('standort', $standort);
	}

	/**
	 * action update
	 * 
	 * @param \Pmwebdesign\Staffm\Domain\Model\Standort $standort
	 * @return void
	 */
	public function updateAction(\Pmwebdesign\Staffm\Domain\Model\Standort $standort) {
		$this->addFlashMessage('Standort aktualisiert!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
		$this->standortRepository->update($standort);
		$this->redirect('list');
	}

	/**
	 * action delete
	 * 
	 * @param \Pmwebdesign\Staffm\Domain\Model\Standort $standort
	 * @return void
	 */
	public function deleteAction(\Pmwebdesign\Staffm\Domain\Model\Standort $standort) {
		// Standort löschen von den Mitarbeitern
		foreach ($this->mitarbeiterRepository->findStandortMitarbeiter($standort) as $m) {	
			$m->setStandort(NULL);
			$this->mitarbeiterRepository->update($m);					
		}
		
		$this->addFlashMessage('Standort gelöscht!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
		$this->standortRepository->remove($standort);
		$this->redirect('list');
	}

}