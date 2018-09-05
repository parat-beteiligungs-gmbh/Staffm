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
 * Test case for class Pmwebdesign\Staffm\Controller\TaetigkeitController.
 *
 * @author Markus Puffer <mpuffer@pm-webdesign.bayern>
 */
class TaetigkeitControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \Pmwebdesign\Staffm\Controller\TaetigkeitController
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = $this->getMock('Pmwebdesign\\Staffm\\Controller\\TaetigkeitController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllTaetigkeitsFromRepositoryAndAssignsThemToView() {

		$allTaetigkeits = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$taetigkeitRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\TaetigkeitRepository', array('findAll'), array(), '', FALSE);
		$taetigkeitRepository->expects($this->once())->method('findAll')->will($this->returnValue($allTaetigkeits));
		$this->inject($this->subject, 'taetigkeitRepository', $taetigkeitRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('taetigkeits', $allTaetigkeits);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenTaetigkeitToView() {
		$taetigkeit = new \Pmwebdesign\Staffm\Domain\Model\Taetigkeit();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('taetigkeit', $taetigkeit);

		$this->subject->showAction($taetigkeit);
	}

	/**
	 * @test
	 */
	public function newActionAssignsTheGivenTaetigkeitToView() {
		$taetigkeit = new \Pmwebdesign\Staffm\Domain\Model\Taetigkeit();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('newTaetigkeit', $taetigkeit);
		$this->inject($this->subject, 'view', $view);

		$this->subject->newAction($taetigkeit);
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenTaetigkeitToTaetigkeitRepository() {
		$taetigkeit = new \Pmwebdesign\Staffm\Domain\Model\Taetigkeit();

		$taetigkeitRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\TaetigkeitRepository', array('add'), array(), '', FALSE);
		$taetigkeitRepository->expects($this->once())->method('add')->with($taetigkeit);
		$this->inject($this->subject, 'taetigkeitRepository', $taetigkeitRepository);

		$this->subject->createAction($taetigkeit);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenTaetigkeitToView() {
		$taetigkeit = new \Pmwebdesign\Staffm\Domain\Model\Taetigkeit();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('taetigkeit', $taetigkeit);

		$this->subject->editAction($taetigkeit);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenTaetigkeitInTaetigkeitRepository() {
		$taetigkeit = new \Pmwebdesign\Staffm\Domain\Model\Taetigkeit();

		$taetigkeitRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\TaetigkeitRepository', array('update'), array(), '', FALSE);
		$taetigkeitRepository->expects($this->once())->method('update')->with($taetigkeit);
		$this->inject($this->subject, 'taetigkeitRepository', $taetigkeitRepository);

		$this->subject->updateAction($taetigkeit);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenTaetigkeitFromTaetigkeitRepository() {
		$taetigkeit = new \Pmwebdesign\Staffm\Domain\Model\Taetigkeit();

		$taetigkeitRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\TaetigkeitRepository', array('remove'), array(), '', FALSE);
		$taetigkeitRepository->expects($this->once())->method('remove')->with($taetigkeit);
		$this->inject($this->subject, 'taetigkeitRepository', $taetigkeitRepository);

		$this->subject->deleteAction($taetigkeit);
	}
}
