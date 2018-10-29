<?php
/*
 * This file is part of Soundways\Iso7064
 *
 * (c) Soundways <team@soundways.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code
 */

namespace Soundways\Iso7064;

use InvalidArgumentException;

/**
 * This is the Mod3736 class.
 *
 * @author James Ekrut <james@soundways.com>
 */
class Mod3736
{
	/**
	 * The string to be encoded/checked
	 *
	 * @var string
	 */
	private $code;
	
	/**
	 * The numerical values assigned to valid alphanumeric
	 * characters according to ISO7064:1983
	 *
	 * @var array
	 */
	private static $val_dict = [
		'0' => 0,
		'1' => 1,
		'2' => 2,
		'3' => 3,
		'4' => 4,
		'5' => 5,
		'6' => 6,
		'7' => 7,
		'8' => 8,
		'9' => 9,
		'A' => 10,
		'B' => 11,
		'C' => 12,
		'D' => 13,
		'E' => 14,
		'F' => 15,
		'G' => 16,
		'H' => 17,
		'I' => 18,
		'J' => 19,
		'K' => 20,
		'L' => 21,
		'M' => 22,
		'N' => 23,
		'O' => 24,
		'P' => 25,
		'Q' => 26,
		'R' => 27,
		'S' => 28,
		'T' => 29,
		'U' => 30,
		'V' => 31,
		'W' => 32,
		'X' => 33,
		'Y' => 34,
		'Z' => 35,
	];

	/**
	 * Create a new Mod3736 instance.
	 *
	 * @param string $code default NULL
	 *
	 * @return void
	 */
	public function __construct(?string $code = NULL) {
		if ($code) {
			$this->code = self::parseCode($code);
		}
	}
	
	/**
	 * Generate a check character for the current code,
	 * then append it to and return the current code.
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return string
	 */
	public function encode(): string {
		$this->getCodeOrFail();
		$this->code .= $this->generateCheckChar($this->code);
		return $this->code;
	}
	
	/**
	 * Generate a check character for the given string
	 * or the class's current code.
	 *
	 * @param string $code default NULL
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return string
	 */
	public function generateCheckChar(?string $code = NULL): string {
		if (!$code) { $code = $this->getCodeOrFail(); }
		$p = 36;
		for($j = 0; $j < mb_strlen($code); $j++) {
			$a = self::charToVal($code[$j]);
			$s = ($p % 37) + $a;
			$p = ($s % 36 ?: 36) * 2;
		}
		return self::valToChar(37 - ($p % 37));
	}
	
	/**
	 * Assuming the last character in the given code is 
	 * a check character, validates the check character
	 * for the given code.  Will use the class's code if
	 * one is not passed as an argument.
	 *
	 * @param string $code default NULL
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return bool
	 */
	public function validateCheckChar(?string $code = NULL): bool {
		if (!$code) { $code = $this->getCodeOrFail(); }
		$check_char = self::getCheckChar($code);
		$valid_check_char = $this->generateCheckChar(substr($code, 0, -1));
		return ($check_char == $valid_check_char);
	}
	
	/**
	 * Setter for $this->code.
	 *
	 * @param string $code
	 *
	 * @return void
	 */
	public function setCode(string $code): void {
		$this->code = self::parseCode($code);
	}
	
	/**
	 * Getter for $this->code.
	 *
	 * @return string 
	 */
	public function getCode(): string {
		return $this->code;
	}
	
	/**
	 * Returns the last character in the given string
	 * or the instance's code, assuming that character
	 * is a check character.
	 *
	 * @param string $code default null
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return string
	 */
	public function getCheckChar(?string $code = NULL): string {
		if (!$code) { $code = $this->getCodeOrFail(); }
		return substr($code, -1);
	}

	private function getCodeOrFail(): string {
		if ($this->code == NULL) {
			throw new InvalidArgumentException('Cannot perform method on NULL');
		}
		return $this->code;
	}
	
	/**
	 * Converts given character to it's given value.
	 *
	 * @param string $char
	 *
	 * @return int
	 */
	private static function charToVal(string $char): int {
		return self::$val_dict[$char];
	}
	
	/**
	 * Given a value, returns the character with that value.
	 *
	 * @param int $val
	 *
	 * @return string
	 */
	private static function valToChar(int $val): string {
		return array_search($val, self::$val_dict);
	}
	
	/**
	 * Converts code for use in calculation.
	 *
	 * @param string $code
	 *
	 * @return string
	 */
	private static function parseCode(string $code): string {
		$code = preg_replace('/[^0-9A-Za-z]/', '', $code);
		return strtoupper($code);
	}
}
