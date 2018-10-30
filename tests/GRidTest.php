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

use Soundways\Iso7064\GRid;
use Soundways\Iso7064\GRidException;
use PHPUnit\Framework\TestCase;

/**
 * This is the GRid test class.
 *
 * @author  James Ekrut <james@soundways.com>
 */
class GRidTest extends TestCase
{
	/**
	 * Helper function with valid encoded GRid codes
	 *
	 * @return array
	 */
	public function goodGRidsEncoded(): array
	{
		return [
			'A12425GABC1234002M',
			'A1-2425G-ABC1234002-M,'
		];
	}

	/**
	 * Helper function with valid unencoded GRid codes
	 * 
	 * @return array
	 */
	public function goodGRidsUnencoded(): array
	{
		return [
			'A12425GABC1234002',
			'A1-2425G-ABC1234002',
			'A1-2425G-ABC1234002-',
		];
	}

	/**
	 * Helper function combining valid GRid helpers
	 * 
	 * @return array
	 */
	public function goodGRids(): array
	{
		return array_merge(
			$this->goodGRidsEncoded(), 
			$this->goodGRidsUnencoded()
		);
	}

	public function unencodedAndEncodedGRids(): array
	{
		return [
			['A1-2425G-ABC1234002', 'A1-2425G-ABC1234002-M'],
			
		];
	}

	/**
	 * Test that new instance can be created with null code.
	 * 
	 * @return void
	 */
	public function testCanBeInstantiatedWithNoConstructorArgument(): void
	{
		$this->assertInstanceOf(
			GRid::class,
			new GRid()
		);
	}

	/**
	 * Test that new instance can be created with valid code.
	 * 
	 * @return void
	 */
	public function testCanBeInstantiatedWithGivenGRid(): void
	{
		foreach($this->goodGRids() as $code) {
			$this->assertInstanceOf(
				GRid::class,
				new GRid($code)
			);
		}
	}

	/**
	 * Test that invalid constructor type throws TypeError.
	 * @return [type] [description]
	 */
	public function testRejectsInvalidConstructorTypes(): void
	{
		$this->expectException(\TypeError::class);
		new GRid([]);
	}

	public function testRejectsInvalidSetterTypes(): void
	{
		$this->expectException(\TypeError::class);
		$grid = new GRid();
		$grid->setCode([]);
	}

	// Test cases:
	// Can be instantiated with no constructor arg ~
	// Can be instantiated with constructor arg ~
	// Rejects invalid constructors ~
	// Rejects invalid setters ~
	// Can encode a given GRid with no check char
	// Refuses to encode a GRid with a check char (GRidException)
	// Can encode an external grid with no check char
	// Refuses to encode an external GRid with no check char (GRidException)
	// Can identify correct check char for given GRid
	// Refuses to verify check char for unencoded given GRid
	// Can identify correct check char for external GRid
	// Refuses to verify check char for unencoded external GRid
	// Can identify incorrect check char for given GRid
	// Can identify incorrect check char for external GRid
	// Can statically check a given GRid
	// Statically rejects badly formatted GRid (GRidException)
	// Statically rejects already encoded GRid (GRidException)
	// Can format a GRid code per GRid standard 2.1
	// Refuses to format an unencoded GRid (GRidException)
	// Throws invalidargumentexception when acting on null or empty string
	// Throws GRidException when given a non 17 or 18 char string
	// Throws GRidException when given a string that does not contain the GRid standard ID
}
