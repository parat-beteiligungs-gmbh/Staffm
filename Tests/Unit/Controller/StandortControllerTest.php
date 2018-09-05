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
 * Test case for class Pmwebdesign\Staffm\Controller\StandortController.
 *
 * @author Markus Puffer <mpuffer@pm-webdesign.bayern>
 */
class StandortControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \Pmwebdesign\Staffm\Controller\StandortController
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = $this->getMock('Pmwebdesign\\Staffm\\Controller\\StandortController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllStandortsFromRepositoryAndAssignsThemToView() {

		$allStandorts = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$standortRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\StandortRepository', array('findAll'), array(), '', FALSE);
		$standortRepository->expects($this->once())->method('findAll')->will($this->returnValue($allStandorts));
		$this->inject($this->subject, 'standortRepository', $standortRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('standorts', $allStandorts);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenStandortToView() {
		$standort = new \Pmwebdesign\Staffm\Domain\Model\Standort();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('standort', $standort);

		$this->subject->showAction($standort);
	}

	/**
	 * @test
	 */
	public function newActionAssignsTheGivenStandortToView() {
		$standort = new \Pmwebdesign\Staffm\Domain\Model\Standort();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('newStandort', $standort);
		$this->inject($this->subject, 'view', $view);

		$this->subject->newAction($standort);
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenStandortToStandortRepository() {
		$standort = new \Pmwebdesign\Staffm\Domain\Model\Standort();

		$standortRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\StandortRepository', array('add'), array(), '', FALSE);
		$standortRepository->expects($this->once())->method('add')->with($standort);
		$this->inject($this->subject, 'standortRepository', $standortRepository);

		$this->subject->createAction($standort);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenStandortToView() {
		$standort = new \Pmwebdesign\Staffm\Domain\Model\Standort();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('standort', $standort);

		$this->subject->editAction($standort);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenStandortInStandortRepository() {
		$standort = new \Pmwebdesign\Staffm\Domain\Model\Standort();

		$standortRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\StandortRepository', array('update'), array(), '', FALSE);
		$standortRepository->expects($this->once())->method('update')->with($standort);
		$this->inject($this->subject, 'standortRepository', $standortRepository);

		$this->subject->updateAction($standort);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenStandortFromStandortRepository() {
		$standort = new \Pmwebdesign\Staffm\Domain\Model\Standort();

		$standortRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\StandortRepository', array('remove'), array(), '', FALSE);
		$standortRepository->expects($this->once())->method('remove')->with($standort);
		$this->inject($this->subject, 'standortRepository', $standortRepository);

		$this->subject->deleteAction($standort);
	}
}
