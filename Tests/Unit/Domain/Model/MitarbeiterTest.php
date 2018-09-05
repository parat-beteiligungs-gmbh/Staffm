<?php

namespace Pmwebdesign\Staffm\Tests\Unit\Domain\Model;

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
 * Test case for class \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Markus Puffer <mpuffer@pm-webdesign.bayern>
 */
class MitarbeiterTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
	/**
	 * @var \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = new \Pmwebdesign\Staffm\Domain\Model\Mitarbeiter();
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getPersonalnummerReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getPersonalnummer()
		);
	}

	/**
	 * @test
	 */
	public function setPersonalnummerForStringSetsPersonalnummer() {
		$this->subject->setPersonalnummer('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'personalnummer',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getUsernameReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getUsername()
		);
	}

	/**
	 * @test
	 */
	public function setUsernameForStringSetsUsername() {
		$this->subject->setUsername('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'username',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getDateOfBirthReturnsInitialValueForInteger() {
		$this->assertSame(
			0,
			$this->subject->getDateOfBirth()
		);
	}

	/**
	 * @test
	 */
	public function setDateOfBirthForIntegerSetsDateOfBirth() {
		$this->subject->setDateOfBirth(12);

		$this->assertAttributeEquals(
			12,
			'dateOfBirth',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getLastNameReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getLastName()
		);
	}

	/**
	 * @test
	 */
	public function setLastNameForStringSetsLastName() {
		$this->subject->setLastName('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'lastName',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getFirstNameReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getFirstName()
		);
	}

	/**
	 * @test
	 */
	public function setFirstNameForStringSetsFirstName() {
		$this->subject->setFirstName('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'firstName',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getTelephoneReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getTelephone()
		);
	}

	/**
	 * @test
	 */
	public function setTelephoneForStringSetsTelephone() {
		$this->subject->setTelephone('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'telephone',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getFaxReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getFax()
		);
	}

	/**
	 * @test
	 */
	public function setFaxForStringSetsFax() {
		$this->subject->setFax('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'fax',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getHandyReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getHandy()
		);
	}

	/**
	 * @test
	 */
	public function setHandyForStringSetsHandy() {
		$this->subject->setHandy('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'handy',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getEmailReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getEmail()
		);
	}

	/**
	 * @test
	 */
	public function setEmailForStringSetsEmail() {
		$this->subject->setEmail('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'email',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getTitleReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getTitle()
		);
	}

	/**
	 * @test
	 */
	public function setTitleForStringSetsTitle() {
		$this->subject->setTitle('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'title',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getKostenstelleReturnsInitialValueForKostenstelle() {
		$this->assertEquals(
			NULL,
			$this->subject->getKostenstelle()
		);
	}

	/**
	 * @test
	 */
	public function setKostenstelleForKostenstelleSetsKostenstelle() {
		$kostenstelleFixture = new \Pmwebdesign\Staffm\Domain\Model\Kostenstelle();
		$this->subject->setKostenstelle($kostenstelleFixture);

		$this->assertAttributeEquals(
			$kostenstelleFixture,
			'kostenstelle',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getFirmaReturnsInitialValueForFirma() {
		$this->assertEquals(
			NULL,
			$this->subject->getFirma()
		);
	}

	/**
	 * @test
	 */
	public function setFirmaForFirmaSetsFirma() {
		$firmaFixture = new \Pmwebdesign\Staffm\Domain\Model\Firma();
		$this->subject->setFirma($firmaFixture);

		$this->assertAttributeEquals(
			$firmaFixture,
			'firma',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getStandortReturnsInitialValueForStandort() {
		$this->assertEquals(
			NULL,
			$this->subject->getStandort()
		);
	}

	/**
	 * @test
	 */
	public function setStandortForStandortSetsStandort() {
		$standortFixture = new \Pmwebdesign\Staffm\Domain\Model\Standort();
		$this->subject->setStandort($standortFixture);

		$this->assertAttributeEquals(
			$standortFixture,
			'standort',
			$this->subject
		);
	}
}
