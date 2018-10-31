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
			'A1-2425G-ABC1234002-M',
			'A12425GXYZ98760086',
			'A1-2425G-XYZ9876008-6',
			'A12425G1234567890L',
			'A1-2425G-1234567890-L',
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
			'A12425GXYZ9876008',
			'A1-2425G-XYZ9876008',
			'A1-2425G-XYZ9876008-',
			'A12425G1234567890',
			'A1-2425G-1234567890',
			'A1-2425G-1234567890-',
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

	/**
	 * Helper function providing badly encoded GRid codes
	 * 
	 * @return array
	 */
	public function badGRidsEncoded(): array
	{
		return [
			'A12425GABC1234002H',
			'A1-2425G-ABC1234002-E',
			'A12425GXYZ98760083',
			'A1-2425G-XYZ9876008-8',
			'A12425G1234567890R',
			'A1-2425G-1234567890-X',
		];
	}

	/**
	 * Helper function with unencoded and correctly coded 
	 * versions of GRid codes.
	 * 
	 * @return array
	 */
	public function unencodedAndEncodedGRids(): array
	{
		return [
			['A12425GABC1234002', 'A12425GABC1234002M'],
			['A12425GXYZ9876008', 'A12425GXYZ98760086'],
			['A12425G1234567890', 'A12425G1234567890L'],
		];
	}

	/**
	 * Helper function with codes that aren't valid GRid format
	 * 
	 * @return array
	 */
	public function badlyFormattedGRids(): array
	{
		return [
			'ABCDEFG',
			'A2-2425G-ABC1234002-M',
			'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
			'@&$*()%^&*^&^&^#@$&*^(&*@#^&$(',
		];
	}

	/**
	 * Test that new instance can be created with valid code.
	 */
	public function testCanBeInstantiatedWithGivenGrid(): void
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
	 */
	public function testRejectsInvalidConstructorTypes(): void
	{
		$this->expectException(\TypeError::class);
		new GRid([]);
	}

	/**
	 * Test that invalid setter type throws TypeError
	 */
	public function testRejectsInvalidSetterTypes(): void
	{
		$this->expectException(\TypeError::class);
		$grid = new GRid($this->goodGridsEncoded()[0]);
		$grid->setCode([]);
	}

	/**
	 * Test that GRid::encode returns correctly encoded GRids
	 */
	public function testEncodesConstructedGrid(): void
	{
		foreach($this->unencodedAndEncodedGRids() as $code) {
			$grid = new GRid($code[0]);
			$this->assertEquals(
				$code[1],
				$grid->encode()
			);
		}
	}

	/**
	 * Test that GRid::encode throws a GRidException when
	 * attempting to encode an already-encoded GRid
	 */
	public function testRejectsAttemptToEncodeAnAlreadyEncodedGrid(): void
	{
		$this->expectException(GRidException::class);
		$grid = new GRid($this->goodGRidsEncoded()[0]);
		$grid->encode();
	}

	/**
	 * Test that valid GRid codes are properly verified.
	 */
	public function testVerifiesValidGridCodes(): void
	{
		foreach($this->goodGRidsEncoded() as $code) {
			$grid = new GRid($code);
			$this->assertTrue($grid->validateCheckChar());
		}
	}

	/**
	 * Test that no attempt is made to verify validity of unencoded
	 * GRids and the correct format exception is thrown.
	 */
	public function testRejectsVerificationForUnencodedGrids(): void
	{
		$this->expectException(GRidException::class);
		$grid = new GRid($this->goodGRidsUnencoded()[0]);
		$grid->validateCheckChar();
	}

	/**
	 * Test that badly encoded GRid codes are identified
	 */
	public function testIdentifiesInvalidGridCodes(): void
	{
		foreach($this->badGRidsEncoded() as $code) {
			$grid = new GRid($code);
			$this->assertFalse($grid->validateCheckChar());
		}
	} 

	/**
	 * Test that valid GRids can be verified statically.
	 */
	public function testCanStaticallyValidateGridCodes(): void
	{
		foreach($this->goodGRidsEncoded() as $code) {
			$this->assertTrue(GRid::checkGRid($code));
		}
	} 

	/**
	 * Test that static GRid verification rejects badly formatted codes.
	 */
	public function testRejectsBadlyFormattedGridsDuringStaticCheck(): void
	{
		$this->expectException(GRidException::class);
		GRid::checkGRid($this->badlyFormattedGRids()[0]);
	}

	/**
	 * Test that static GRid verification rejects good but
	 * unencoded GRid codes
	 */
	public function testRejectsUnencodedGridDuringStaticCheck(): void
	{
		$this->expectException(GRidException::class);
		GRid::checkGRid($this->goodGRidsUnencoded()[0]);
	}

	/**
	 * Test that encoded GRids are exported in correct format
	 */
	public function testExportsGridFormat(): void
	{
		foreach($this->goodGRidsUnencoded() as $code) {
			$grid = new GRid($code);
			$grid->encode();
			$this->assertTrue(preg_match(
				'/A1-[0-9A-Za-z]{5}-[0-9A-Za-z]{10}-[0-9A-Za-z]/', 
				$grid->getDelimitedGRid()
			) == 1);
		}
	}

	/**
	 * Test that formatting exception gets thrown when trying
	 * to get formatted version of an unencoded GRid
	 */
	public function testRejectsFormatCallOnUnencodedGrid(): void
	{
		$this->expectException(GRidException::class);
		$grid = new GRid($this->goodGRidsUnencoded()[0]);
		$grid->getDelimitedGRid();
	}

	/**
	 * Test that given codes that are the wrong length are
	 * rejected during instantiation.
	 */
	public function testThrowsGridExceptionOnInvalidString(): void
	{
		$this->expectException(GRidException::class);
		$grid = new GRid($this->badlyFormattedGRids()[0]);
	}

	/**
	 * Test that given codes that are the correct format
	 * but are missing the GRid identifier (A1) are rejected
	 */
	public function testThrowsGridExceptionWhenGridIdentifierIsMissing(): void
	{
		$this->expectException(GRidException::class);
		$grid = new GRid($this->badlyFormattedGRids()[1]);
	}
}
