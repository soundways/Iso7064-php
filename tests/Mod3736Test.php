<?php
/*
 * This file is part of Soundways\Iso7064
 *
 * (c) Soundways <team@soundways.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code
 */

namespace Soundways\Iso7064\Tests;

use Soundways\Iso7064\Mod3736;
use PHPUnit\Framework\TestCase;

/**
 * This is the Mod3736 test class.
 *
 * @author  James Ekrut <james@soundways.com>
 */
class Mod3736Test extends TestCase
{	
	/**
	 * Test that new instance can be created with code.
	 */
	public function testCanBeInstantiatedWithGivenString(): void
	{
		$this->assertInstanceOf(
			Mod3736::class,
			new Mod3736('ABCDEFG')
		);
	}

	/**
	 * Test that constructor rejects types which cannot be
	 * cast to string.
	 */
	public function testRejectsInvalidConstructorTypes(): void
	{
		$this->expectException(\TypeError::class);
		new Mod3736([]);
	}

	/**
	 * Test that setter rejects types which cannot be cast
	 * to string.
	 * 
	 * @return void 
	 */
	public function testRejectsInvalidTypesInSetter(): void
	{
		$mod = new Mod3736('string');
		$this->expectException(\TypeError::class);
		$mod->setCode([]);
	}

	/**
	 * Test that instance with class code can properly encode.
	 */
	public function testCanEncodeAGivenString(): void
	{
		$mod = new Mod3736('ABCDEFG');
		$mod->encode();
		$this->assertEquals(
			'ABCDEFGR',
			$mod->getCode()
		);
	}

	/**
	 * Test that check character validation can detect
	 * correct check characters.
	 */
	public function testCanIdentifyCorrectlyFormattedCheckChars(): void
	{
		$mod = new Mod3736('XYZ123I');
		$this->assertTrue($mod->validateCheckChar());
	}

	/**
	 * Test that check character validation can detect 
	 * incorrect check characters.
	 */
	public function testCanIdentifyIncorrectlyFormattedCheckChars(): void
	{
		$mod = new Mod3736('XYZ123H');
		$this->assertFalse($mod->validateCheckChar());
	}
}
