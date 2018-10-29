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
 * This is the Mod3076 test class.
 *
 * @author  James Ekrut <james@soundways.com>
 */
class Mod4746Test extends TestCase
{	
	/**
	 * Test that new instance can be created with null code.
	 * 
	 * @return void
	 */
	public function testCanBeInstantiatedWithNoConstructorArgument(): void
	{
		$this->assertInstanceOf(
			Mod3736::class,
			new Mod3736()
		);
	}

	/**
	 * Test that new instance can be created with code.
	 * 
	 * @return void 
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
	 * 
	 * @return void
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
		$mod = new Mod3736();
		$this->expectException(\TypeError::class);
		$mod->setCode([]);
	}

	/**
	 * Test that instance with class code can properly encode.
	 *
	 * @return void
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
	 * Test that object instance can generate char for external string.
	 * 
	 * @return void 
	 */
	public function testCanEncodeAnExternalString(): void
	{
		$mod = new Mod3736();
		$this->assertEquals(
			'R',
			$mod->generateCheckChar('ABCDEFG')
		);
	}

	/**
	 * Test that check character validation can detect
	 * correct check characters.
	 * 
	 * @return void
	 */
	public function testCanIdentifyCorrectlyFormattedCheckChars(): void
	{
		$mod = new Mod3736('XYZ123I');
		$this->assertTrue($mod->validateCheckChar());
	}

	/**
	 * Test that check character validation can detect 
	 * incorrect check characters.
	 * 
	 * @return void 
	 */
	public function testCanIdentifyIncorrectlyFormattedCheckChars(): void
	{
		$mod = new Mod3736('XYZ123H');
		$this->assertFalse($mod->validateCheckChar());
	}

	/**
	 * Test that encoding functions called on null or empty
	 * strings fail correctly.
	 * 
	 * @return void
	 */
	public function testThrowsInvalidArgumentExceptionWhenNoStringIsProvided(): void
	{
		$mod = new Mod3736();
		$this->expectException(\InvalidArgumentException::class);
		$mod->encode();
	}

}
