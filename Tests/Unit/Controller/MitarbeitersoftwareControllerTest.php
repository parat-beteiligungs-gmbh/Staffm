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
 * Test case for class Pmwebdesign\Staffm\Controller\MitarbeitersoftwareController.
 *
 * @author Markus Puffer <mpuffer@pm-webdesign.bayern>
 */
class MitarbeitersoftwareControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \Pmwebdesign\Staffm\Controller\MitarbeitersoftwareController
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = $this->getMock('Pmwebdesign\\Staffm\\Controller\\MitarbeitersoftwareController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllMitarbeitersoftwaresFromRepositoryAndAssignsThemToView() {

		$allMitarbeitersoftwares = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$mitarbeitersoftwareRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeitersoftwareRepository', array('findAll'), array(), '', FALSE);
		$mitarbeitersoftwareRepository->expects($this->once())->method('findAll')->will($this->returnValue($allMitarbeitersoftwares));
		$this->inject($this->subject, 'mitarbeitersoftwareRepository', $mitarbeitersoftwareRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('mitarbeitersoftwares', $allMitarbeitersoftwares);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenMitarbeitersoftwareToView() {
		$mitarbeitersoftware = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeitersoftware();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('mitarbeitersoftware', $mitarbeitersoftware);

		$this->subject->showAction($mitarbeitersoftware);
	}

	/**
	 * @test
	 */
	public function newActionAssignsTheGivenMitarbeitersoftwareToView() {
		$mitarbeitersoftware = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeitersoftware();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('newMitarbeitersoftware', $mitarbeitersoftware);
		$this->inject($this->subject, 'view', $view);

		$this->subject->newAction($mitarbeitersoftware);
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenMitarbeitersoftwareToMitarbeitersoftwareRepository() {
		$mitarbeitersoftware = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeitersoftware();

		$mitarbeitersoftwareRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeitersoftwareRepository', array('add'), array(), '', FALSE);
		$mitarbeitersoftwareRepository->expects($this->once())->method('add')->with($mitarbeitersoftware);
		$this->inject($this->subject, 'mitarbeitersoftwareRepository', $mitarbeitersoftwareRepository);

		$this->subject->createAction($mitarbeitersoftware);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenMitarbeitersoftwareToView() {
		$mitarbeitersoftware = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeitersoftware();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('mitarbeitersoftware', $mitarbeitersoftware);

		$this->subject->editAction($mitarbeitersoftware);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenMitarbeitersoftwareInMitarbeitersoftwareRepository() {
		$mitarbeitersoftware = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeitersoftware();

		$mitarbeitersoftwareRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeitersoftwareRepository', array('update'), array(), '', FALSE);
		$mitarbeitersoftwareRepository->expects($this->once())->method('update')->with($mitarbeitersoftware);
		$this->inject($this->subject, 'mitarbeitersoftwareRepository', $mitarbeitersoftwareRepository);

		$this->subject->updateAction($mitarbeitersoftware);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenMitarbeitersoftwareFromMitarbeitersoftwareRepository() {
		$mitarbeitersoftware = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeitersoftware();

		$mitarbeitersoftwareRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\MitarbeitersoftwareRepository', array('remove'), array(), '', FALSE);
		$mitarbeitersoftwareRepository->expects($this->once())->method('remove')->with($mitarbeitersoftware);
		$this->inject($this->subject, 'mitarbeitersoftwareRepository', $mitarbeitersoftwareRepository);

		$this->subject->deleteAction($mitarbeitersoftware);
	}
}
