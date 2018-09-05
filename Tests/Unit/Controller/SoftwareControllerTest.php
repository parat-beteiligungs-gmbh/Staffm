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
 * Test case for class Pmwebdesign\Staffm\Controller\SoftwareController.
 *
 * @author Markus Puffer <mpuffer@pm-webdesign.bayern>
 */
class SoftwareControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \Pmwebdesign\Staffm\Controller\SoftwareController
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = $this->getMock('Pmwebdesign\\Staffm\\Controller\\SoftwareController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllSoftwaresFromRepositoryAndAssignsThemToView() {

		$allSoftwares = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$softwareRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\SoftwareRepository', array('findAll'), array(), '', FALSE);
		$softwareRepository->expects($this->once())->method('findAll')->will($this->returnValue($allSoftwares));
		$this->inject($this->subject, 'softwareRepository', $softwareRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('softwares', $allSoftwares);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenSoftwareToView() {
		$software = new \Pmwebdesign\Staffm\Domain\Model\Software();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('software', $software);

		$this->subject->showAction($software);
	}

	/**
	 * @test
	 */
	public function newActionAssignsTheGivenSoftwareToView() {
		$software = new \Pmwebdesign\Staffm\Domain\Model\Software();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('newSoftware', $software);
		$this->inject($this->subject, 'view', $view);

		$this->subject->newAction($software);
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenSoftwareToSoftwareRepository() {
		$software = new \Pmwebdesign\Staffm\Domain\Model\Software();

		$softwareRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\SoftwareRepository', array('add'), array(), '', FALSE);
		$softwareRepository->expects($this->once())->method('add')->with($software);
		$this->inject($this->subject, 'softwareRepository', $softwareRepository);

		$this->subject->createAction($software);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenSoftwareToView() {
		$software = new \Pmwebdesign\Staffm\Domain\Model\Software();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('software', $software);

		$this->subject->editAction($software);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenSoftwareInSoftwareRepository() {
		$software = new \Pmwebdesign\Staffm\Domain\Model\Software();

		$softwareRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\SoftwareRepository', array('update'), array(), '', FALSE);
		$softwareRepository->expects($this->once())->method('update')->with($software);
		$this->inject($this->subject, 'softwareRepository', $softwareRepository);

		$this->subject->updateAction($software);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenSoftwareFromSoftwareRepository() {
		$software = new \Pmwebdesign\Staffm\Domain\Model\Software();

		$softwareRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\SoftwareRepository', array('remove'), array(), '', FALSE);
		$softwareRepository->expects($this->once())->method('remove')->with($software);
		$this->inject($this->subject, 'softwareRepository', $softwareRepository);

		$this->subject->deleteAction($software);
	}
}
