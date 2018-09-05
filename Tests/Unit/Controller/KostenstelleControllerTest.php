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
 * Test case for class Pmwebdesign\Staffm\Controller\KostenstelleController.
 *
 * @author Markus Puffer <mpuffer@pm-webdesign.bayern>
 */
class KostenstelleControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \Pmwebdesign\Staffm\Controller\KostenstelleController
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = $this->getMock('Pmwebdesign\\Staffm\\Controller\\KostenstelleController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllKostenstellesFromRepositoryAndAssignsThemToView() {

		$allKostenstelles = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$kostenstelleRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\KostenstelleRepository', array('findAll'), array(), '', FALSE);
		$kostenstelleRepository->expects($this->once())->method('findAll')->will($this->returnValue($allKostenstelles));
		$this->inject($this->subject, 'kostenstelleRepository', $kostenstelleRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('kostenstelles', $allKostenstelles);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenKostenstelleToView() {
		$kostenstelle = new \Pmwebdesign\Staffm\Domain\Model\Kostenstelle();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('kostenstelle', $kostenstelle);

		$this->subject->showAction($kostenstelle);
	}

	/**
	 * @test
	 */
	public function newActionAssignsTheGivenKostenstelleToView() {
		$kostenstelle = new \Pmwebdesign\Staffm\Domain\Model\Kostenstelle();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('newKostenstelle', $kostenstelle);
		$this->inject($this->subject, 'view', $view);

		$this->subject->newAction($kostenstelle);
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenKostenstelleToKostenstelleRepository() {
		$kostenstelle = new \Pmwebdesign\Staffm\Domain\Model\Kostenstelle();

		$kostenstelleRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\KostenstelleRepository', array('add'), array(), '', FALSE);
		$kostenstelleRepository->expects($this->once())->method('add')->with($kostenstelle);
		$this->inject($this->subject, 'kostenstelleRepository', $kostenstelleRepository);

		$this->subject->createAction($kostenstelle);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenKostenstelleToView() {
		$kostenstelle = new \Pmwebdesign\Staffm\Domain\Model\Kostenstelle();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('kostenstelle', $kostenstelle);

		$this->subject->editAction($kostenstelle);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenKostenstelleInKostenstelleRepository() {
		$kostenstelle = new \Pmwebdesign\Staffm\Domain\Model\Kostenstelle();

		$kostenstelleRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\KostenstelleRepository', array('update'), array(), '', FALSE);
		$kostenstelleRepository->expects($this->once())->method('update')->with($kostenstelle);
		$this->inject($this->subject, 'kostenstelleRepository', $kostenstelleRepository);

		$this->subject->updateAction($kostenstelle);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenKostenstelleFromKostenstelleRepository() {
		$kostenstelle = new \Pmwebdesign\Staffm\Domain\Model\Kostenstelle();

		$kostenstelleRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\KostenstelleRepository', array('remove'), array(), '', FALSE);
		$kostenstelleRepository->expects($this->once())->method('remove')->with($kostenstelle);
		$this->inject($this->subject, 'kostenstelleRepository', $kostenstelleRepository);

		$this->subject->deleteAction($kostenstelle);
	}
}
