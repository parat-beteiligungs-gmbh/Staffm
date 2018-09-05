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
 * Test case for class Pmwebdesign\Staffm\Controller\QualifikationController.
 *
 * @author Markus Puffer <mpuffer@pm-webdesign.bayern>
 */
class QualifikationControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \Pmwebdesign\Staffm\Controller\QualifikationController
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = $this->getMock('Pmwebdesign\\Staffm\\Controller\\QualifikationController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllQualifikationsFromRepositoryAndAssignsThemToView() {

		$allQualifikations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$qualifikationRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\QualifikationRepository', array('findAll'), array(), '', FALSE);
		$qualifikationRepository->expects($this->once())->method('findAll')->will($this->returnValue($allQualifikations));
		$this->inject($this->subject, 'qualifikationRepository', $qualifikationRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('qualifikations', $allQualifikations);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenQualifikationToView() {
		$qualifikation = new \Pmwebdesign\Staffm\Domain\Model\Qualifikation();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('qualifikation', $qualifikation);

		$this->subject->showAction($qualifikation);
	}

	/**
	 * @test
	 */
	public function newActionAssignsTheGivenQualifikationToView() {
		$qualifikation = new \Pmwebdesign\Staffm\Domain\Model\Qualifikation();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('newQualifikation', $qualifikation);
		$this->inject($this->subject, 'view', $view);

		$this->subject->newAction($qualifikation);
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenQualifikationToQualifikationRepository() {
		$qualifikation = new \Pmwebdesign\Staffm\Domain\Model\Qualifikation();

		$qualifikationRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\QualifikationRepository', array('add'), array(), '', FALSE);
		$qualifikationRepository->expects($this->once())->method('add')->with($qualifikation);
		$this->inject($this->subject, 'qualifikationRepository', $qualifikationRepository);

		$this->subject->createAction($qualifikation);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenQualifikationToView() {
		$qualifikation = new \Pmwebdesign\Staffm\Domain\Model\Qualifikation();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('qualifikation', $qualifikation);

		$this->subject->editAction($qualifikation);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenQualifikationInQualifikationRepository() {
		$qualifikation = new \Pmwebdesign\Staffm\Domain\Model\Qualifikation();

		$qualifikationRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\QualifikationRepository', array('update'), array(), '', FALSE);
		$qualifikationRepository->expects($this->once())->method('update')->with($qualifikation);
		$this->inject($this->subject, 'qualifikationRepository', $qualifikationRepository);

		$this->subject->updateAction($qualifikation);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenQualifikationFromQualifikationRepository() {
		$qualifikation = new \Pmwebdesign\Staffm\Domain\Model\Qualifikation();

		$qualifikationRepository = $this->getMock('Pmwebdesign\\Staffm\\Domain\\Repository\\QualifikationRepository', array('remove'), array(), '', FALSE);
		$qualifikationRepository->expects($this->once())->method('remove')->with($qualifikation);
		$this->inject($this->subject, 'qualifikationRepository', $qualifikationRepository);

		$this->subject->deleteAction($qualifikation);
	}
}
