<?php
namespace Pmwebdesign\Staffm\Tests\Unit\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Markus Puffer <mpuffer@pm-webdesign.bayern>, PM-Webdesign
 *  			
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Test case for class Pmwebdesign\Staffm\Controller\MitarbeiterqualifikationController.
 *
 * @author Markus Puffer <mpuffer@pm-webdesign.bayern>
 */
class MitarbeiterqualifikationControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \Pmwebdesign\Staffm\Controller\MitarbeiterqualifikationController
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = $this->getMock('Pmwebdesign\\Staffm\\Controller\\MitarbeiterqualifikationController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllMitarbeiterqualifikationsFromRepositoryAndAssignsThemToView() {

		$allMitarbeiterqualifikations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$mitarbeiterqualifikationRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterqualifikationRepository', array('findAll'), array(), '', FALSE);
		$mitarbeiterqualifikationRepository->expects($this->once())->method('findAll')->will($this->returnValue($allMitarbeiterqualifikations));
		$this->inject($this->subject, 'mitarbeiterqualifikationRepository', $mitarbeiterqualifikationRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('mitarbeiterqualifikations', $allMitarbeiterqualifikations);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenMitarbeiterqualifikationToView() {
		$mitarbeiterqualifikation = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiterqualifikation();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('mitarbeiterqualifikation', $mitarbeiterqualifikation);

		$this->subject->showAction($mitarbeiterqualifikation);
	}

	/**
	 * @test
	 */
	public function newActionAssignsTheGivenMitarbeiterqualifikationToView() {
		$mitarbeiterqualifikation = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiterqualifikation();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('newMitarbeiterqualifikation', $mitarbeiterqualifikation);
		$this->inject($this->subject, 'view', $view);

		$this->subject->newAction($mitarbeiterqualifikation);
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenMitarbeiterqualifikationToMitarbeiterqualifikationRepository() {
		$mitarbeiterqualifikation = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiterqualifikation();

		$mitarbeiterqualifikationRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterqualifikationRepository', array('add'), array(), '', FALSE);
		$mitarbeiterqualifikationRepository->expects($this->once())->method('add')->with($mitarbeiterqualifikation);
		$this->inject($this->subject, 'mitarbeiterqualifikationRepository', $mitarbeiterqualifikationRepository);

		$this->subject->createAction($mitarbeiterqualifikation);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenMitarbeiterqualifikationToView() {
		$mitarbeiterqualifikation = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiterqualifikation();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('mitarbeiterqualifikation', $mitarbeiterqualifikation);

		$this->subject->editAction($mitarbeiterqualifikation);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenMitarbeiterqualifikationInMitarbeiterqualifikationRepository() {
		$mitarbeiterqualifikation = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiterqualifikation();

		$mitarbeiterqualifikationRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterqualifikationRepository', array('update'), array(), '', FALSE);
		$mitarbeiterqualifikationRepository->expects($this->once())->method('update')->with($mitarbeiterqualifikation);
		$this->inject($this->subject, 'mitarbeiterqualifikationRepository', $mitarbeiterqualifikationRepository);

		$this->subject->updateAction($mitarbeiterqualifikation);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenMitarbeiterqualifikationFromMitarbeiterqualifikationRepository() {
		$mitarbeiterqualifikation = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiterqualifikation();

		$mitarbeiterqualifikationRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeiterqualifikationRepository', array('remove'), array(), '', FALSE);
		$mitarbeiterqualifikationRepository->expects($this->once())->method('remove')->with($mitarbeiterqualifikation);
		$this->inject($this->subject, 'mitarbeiterqualifikationRepository', $mitarbeiterqualifikationRepository);

		$this->subject->deleteAction($mitarbeiterqualifikation);
	}
}
