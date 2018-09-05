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
 * Test case for class Pmwebdesign\Staffm\Controller\MitarbeiterController.
 *
 * @author Markus Puffer <mpuffer@pm-webdesign.bayern>
 */
class MitarbeiterControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \Pmwebdesign\Staffm\Controller\MitarbeiterController
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = $this->getMock('Pmwebdesign\\Staffm\\Controller\\MitarbeiterController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllMitarbeitersFromRepositoryAndAssignsThemToView() {

		$allMitarbeiters = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$mitarbeiterRepository = $this->getMock('', array('findAll'), array(), '', FALSE);
		$mitarbeiterRepository->expects($this->once())->method('findAll')->will($this->returnValue($allMitarbeiters));
		$this->inject($this->subject, 'mitarbeiterRepository', $mitarbeiterRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('mitarbeiters', $allMitarbeiters);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenMitarbeiterToView() {
		$mitarbeiter = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('mitarbeiter', $mitarbeiter);

		$this->subject->showAction($mitarbeiter);
	}

	/**
	 * @test
	 */
	public function newActionAssignsTheGivenMitarbeiterToView() {
		$mitarbeiter = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('newMitarbeiter', $mitarbeiter);
		$this->inject($this->subject, 'view', $view);

		$this->subject->newAction($mitarbeiter);
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenMitarbeiterToMitarbeiterRepository() {
		$mitarbeiter = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter();

		$mitarbeiterRepository = $this->getMock('', array('add'), array(), '', FALSE);
		$mitarbeiterRepository->expects($this->once())->method('add')->with($mitarbeiter);
		$this->inject($this->subject, 'mitarbeiterRepository', $mitarbeiterRepository);

		$this->subject->createAction($mitarbeiter);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenMitarbeiterToView() {
		$mitarbeiter = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('mitarbeiter', $mitarbeiter);

		$this->subject->editAction($mitarbeiter);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenMitarbeiterInMitarbeiterRepository() {
		$mitarbeiter = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter();

		$mitarbeiterRepository = $this->getMock('', array('update'), array(), '', FALSE);
		$mitarbeiterRepository->expects($this->once())->method('update')->with($mitarbeiter);
		$this->inject($this->subject, 'mitarbeiterRepository', $mitarbeiterRepository);

		$this->subject->updateAction($mitarbeiter);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenMitarbeiterFromMitarbeiterRepository() {
		$mitarbeiter = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter();

		$mitarbeiterRepository = $this->getMock('', array('remove'), array(), '', FALSE);
		$mitarbeiterRepository->expects($this->once())->method('remove')->with($mitarbeiter);
		$this->inject($this->subject, 'mitarbeiterRepository', $mitarbeiterRepository);

		$this->subject->deleteAction($mitarbeiter);
	}
}
