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
 * Test case for class Pmwebdesign\Staffm\Controller\FirmaController.
 *
 * @author Markus Puffer <mpuffer@pm-webdesign.bayern>
 */
class FirmaControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \Pmwebdesign\Staffm\Controller\FirmaController
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = $this->getMock('Pmwebdesign\\Staffm\\Controller\\FirmaController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllFirmasFromRepositoryAndAssignsThemToView() {

		$allFirmas = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$firmaRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\FirmaRepository', array('findAll'), array(), '', FALSE);
		$firmaRepository->expects($this->once())->method('findAll')->will($this->returnValue($allFirmas));
		$this->inject($this->subject, 'firmaRepository', $firmaRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('firmas', $allFirmas);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenFirmaToView() {
		$firma = new \Pmwebdesign\Staffm\Domain\Model\Firma();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('firma', $firma);

		$this->subject->showAction($firma);
	}

	/**
	 * @test
	 */
	public function newActionAssignsTheGivenFirmaToView() {
		$firma = new \Pmwebdesign\Staffm\Domain\Model\Firma();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('newFirma', $firma);
		$this->inject($this->subject, 'view', $view);

		$this->subject->newAction($firma);
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenFirmaToFirmaRepository() {
		$firma = new \Pmwebdesign\Staffm\Domain\Model\Firma();

		$firmaRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\FirmaRepository', array('add'), array(), '', FALSE);
		$firmaRepository->expects($this->once())->method('add')->with($firma);
		$this->inject($this->subject, 'firmaRepository', $firmaRepository);

		$this->subject->createAction($firma);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenFirmaToView() {
		$firma = new \Pmwebdesign\Staffm\Domain\Model\Firma();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('firma', $firma);

		$this->subject->editAction($firma);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenFirmaInFirmaRepository() {
		$firma = new \Pmwebdesign\Staffm\Domain\Model\Firma();

		$firmaRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\FirmaRepository', array('update'), array(), '', FALSE);
		$firmaRepository->expects($this->once())->method('update')->with($firma);
		$this->inject($this->subject, 'firmaRepository', $firmaRepository);

		$this->subject->updateAction($firma);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenFirmaFromFirmaRepository() {
		$firma = new \Pmwebdesign\Staffm\Domain\Model\Firma();

		$firmaRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\FirmaRepository', array('remove'), array(), '', FALSE);
		$firmaRepository->expects($this->once())->method('remove')->with($firma);
		$this->inject($this->subject, 'firmaRepository', $firmaRepository);

		$this->subject->deleteAction($firma);
	}
}
