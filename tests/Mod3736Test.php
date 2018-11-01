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
use Soundways\Iso7064\Iso7064FormattingException;

use PHPUnit\Framework\TestCase;

/**
 * This is the Mod3736 test class.
 *
 * @author  James Ekrut <james@soundways.com>
 */
class Mod3736Test extends TestCase
{	
	public function formatOptions(): array
	{
		return [
			[
				'code' => 'ABCDEFGR',
				'lengths' => [1, 3, 3, 1],
				'delim' => '-',
				'formatted' => 'A-BCD-EFG-R',
			],
			[
				'code' => 'ABCDEFGR',
				'lengths' => [1, 2, 2, 3],
				'delim' => '/',
				'formatted' => 'A/BC/DE/FGR',
			],
			[
				'code' => 'XYZ123I',
				'lengths' => [4, 2, 1],
				'delim' => ';',
				'formatted' => 'XYZ1;23;I',
			],
			[
				'code' => 'XYZ123I',
				'lengths' => [6, 1],
				'delim' => '::',
				'formatted' => 'XYZ123::I',
			],
			[
				'code' => 'XYZ123I',
				'lengths' => [7],
				'delim' => '+',
				'formatted' => 'XYZ123I',
			],
		];
	}
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

	/**
	 * Test that format returns expected values with good input
	 */
	public function testCanFormatWithGivenSequenceLengthsAndDelimiters(): void
	{
		foreach($this->formatOptions() as $option) {
			$mod = new Mod3736($option['code']);
			$this->assertEquals(
				$option['formatted'],
				$mod->format($option['lengths'], $option['delim'])
			);
		}
	}

	/**
	 * Test that format rejects bad lengths
	 */
	public function testRejectsFormattingWithBadLengths(): void
	{
		$this->expectException(Iso7064FormattingException::class);
		$mod = new Mod3736('ABCDEFGR');
		$mod->format([1, 3, 3, 2], '-');
	}

	/**
	 * Test that escape character delimiters are safely rejected
	 */
	public function testFormatWithBadDelimiterFails(): void
	{
		$this->expectException(Iso7064FormattingException::class);
		$mod = new Mod3736('ABCDEFGR');
		$mod->format([1, 3, 3, 1], '\\');
	}
}
