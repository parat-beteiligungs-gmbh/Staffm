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
 * Test case for class Pmwebdesign\Staffm\Controller\MitarbeitertaetigkeitController.
 *
 * @author Markus Puffer <mpuffer@pm-webdesign.bayern>
 */
class MitarbeitertaetigkeitControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \Pmwebdesign\Staffm\Controller\MitarbeitertaetigkeitController
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = $this->getMock('Pmwebdesign\\Staffm\\Controller\\MitarbeitertaetigkeitController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllMitarbeitertaetigkeitsFromRepositoryAndAssignsThemToView() {

		$allMitarbeitertaetigkeits = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$mitarbeitertaetigkeitRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeitertaetigkeitRepository', array('findAll'), array(), '', FALSE);
		$mitarbeitertaetigkeitRepository->expects($this->once())->method('findAll')->will($this->returnValue($allMitarbeitertaetigkeits));
		$this->inject($this->subject, 'mitarbeitertaetigkeitRepository', $mitarbeitertaetigkeitRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('mitarbeitertaetigkeits', $allMitarbeitertaetigkeits);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenMitarbeitertaetigkeitToView() {
		$mitarbeitertaetigkeit = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeitertaetigkeit();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('mitarbeitertaetigkeit', $mitarbeitertaetigkeit);

		$this->subject->showAction($mitarbeitertaetigkeit);
	}

	/**
	 * @test
	 */
	public function newActionAssignsTheGivenMitarbeitertaetigkeitToView() {
		$mitarbeitertaetigkeit = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeitertaetigkeit();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('newMitarbeitertaetigkeit', $mitarbeitertaetigkeit);
		$this->inject($this->subject, 'view', $view);

		$this->subject->newAction($mitarbeitertaetigkeit);
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenMitarbeitertaetigkeitToMitarbeitertaetigkeitRepository() {
		$mitarbeitertaetigkeit = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeitertaetigkeit();

		$mitarbeitertaetigkeitRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeitertaetigkeitRepository', array('add'), array(), '', FALSE);
		$mitarbeitertaetigkeitRepository->expects($this->once())->method('add')->with($mitarbeitertaetigkeit);
		$this->inject($this->subject, 'mitarbeitertaetigkeitRepository', $mitarbeitertaetigkeitRepository);

		$this->subject->createAction($mitarbeitertaetigkeit);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenMitarbeitertaetigkeitToView() {
		$mitarbeitertaetigkeit = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeitertaetigkeit();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('mitarbeitertaetigkeit', $mitarbeitertaetigkeit);

		$this->subject->editAction($mitarbeitertaetigkeit);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenMitarbeitertaetigkeitInMitarbeitertaetigkeitRepository() {
		$mitarbeitertaetigkeit = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeitertaetigkeit();

		$mitarbeitertaetigkeitRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeitertaetigkeitRepository', array('update'), array(), '', FALSE);
		$mitarbeitertaetigkeitRepository->expects($this->once())->method('update')->with($mitarbeitertaetigkeit);
		$this->inject($this->subject, 'mitarbeitertaetigkeitRepository', $mitarbeitertaetigkeitRepository);

		$this->subject->updateAction($mitarbeitertaetigkeit);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenMitarbeitertaetigkeitFromMitarbeitertaetigkeitRepository() {
		$mitarbeitertaetigkeit = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeitertaetigkeit();

		$mitarbeitertaetigkeitRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeitertaetigkeitRepository', array('remove'), array(), '', FALSE);
		$mitarbeitertaetigkeitRepository->expects($this->once())->method('remove')->with($mitarbeitertaetigkeit);
		$this->inject($this->subject, 'mitarbeitertaetigkeitRepository', $mitarbeitertaetigkeitRepository);

		$this->subject->deleteAction($mitarbeitertaetigkeit);
	}
}
