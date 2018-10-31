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

/**
 * This is the GRid class.
 *
 * @author James Ekrut <james@soundways.com>
 */
class GRid extends Mod3736
{
	/**
	 * Describes whether the GRid already
	 * has a check character.
	 *
	 * @var bool
	 */
	private $is_encoded;

	/**
	 * Create a new GRid instance.
	 *
	 * @param string $code
	 *
	 * @return void
	 */
	public function __construct(string $code) {
		$this->code = self::parseCode($code);
		$this->checkFormat();
	}
	
	/**
	 * Generate a check character for the current code,
	 * then append it to and return the current code.
	 * Overrides the parent function to also enforce
	 * GRid standard compliance.
	 *
	 * @throws InvalidArgumentException
	 * @throws GRidException
	 *
	 * @return string GRid with newly generated check character
	 */
	public function encode(): string {
		$this->checkFormat();
		if ($this->is_encoded) {
			$error = 'GRid code '
			       . $this->code
			       . ' already contains a check character ('
			       . $this->getCheckChar()
			       . ').';
			throw new GRidException($error);
		}
		return parent::encode();
	}

	/**
	 * Generate a check character for the given
	 *
	 * @param string $code Unencoded GRid
	 *
	 * @throws InvalidArgumentException
	 * @throws GRidException
	 * 
	 * @return string Generated check character
	 */
	public function generateCheckChar(?string $code = NULL): string {
		if (!$code) {
			$code = $this->code;
			$storing_code = false;
		} else {
			$current_code = $this->code;
			$this->code = $code;
			$storing_code = true;
		}
		$this->checkFormat();
		if ($this->is_encoded) {
			$error = 'GRid code '
			       . $this->code
			       . ' already contains a check character ('
			       . $this->getCheckChar()
			       . ').';
			throw new GRidException($error);
		}
		$check_char =  parent::generateCheckChar();
		if ($storing_code) { $this->code = $current_code; }
		return $check_char;
	}
	
	/**
	 * If the instance's code is an encoded GRid,
	 * validates the GRid's check character.
	 *
	 * @throws GRidException
	 * @throws InvalidArgumentException
	 *
	 * @return bool True if valid, false if invalid
	 */
	public function validateCheckChar(): bool {
		$this->checkFormat();

		if (!$this->is_encoded) {
			$error = 'GRid code '
			       . $this->code
			       . ' does not contain a check character.';
			throw new GRidException($error);
		}

		return parent::validateCheckChar();
	}

	/**
	 * Setter for $this->code
	 *
	 * @param string $code
	 *
	 * @throws GRidException
	 *
	 * @return void
	 */
	public function setCode(string $code): void {
		$code = self::parseCode($code);
		$this->checkFormat($code);
		$this->code = $code;
	}

	/**
	 * Helper function which returns the GRid
	 * code in standard hyphen-delimited format.
	 *
	 * @throws GRidException
	 * @throws InvalidArgumentException
	 *
	 * @return string Hypen-delimited GRid
	 */
	public function getDelimitedGRid(): string {
		$grid = $this->code;

		$this->checkFormat($grid);

		if (mb_strlen($grid) == 17) {
			throw new GRidException('Cannot format GRid without check character.  Encode and try again.');
		}

		$pattern = '/(A1)([0-9A-Z]{5})([0-9A-Z]{10})([0-9A-Z])/';

		$replace = '\1-\2-\3-\4';

		$grid = preg_filter($pattern, $replace, $grid);

		return $grid;
	}
	
	/**
	 * Checks that the given code has a valid GRid
	 * length and whether it contains a check character
	 * as per the GRid standard.
	 *
	 * @throws GRidException
	 *
	 * @return void
	 */
	private function checkFormat(): void {
		$char_count = mb_strlen($this->code);

		if ($char_count == 17) {
			$this->is_encoded = false;
		} else if ($char_count == 18) {
			$this->is_encoded = true;
		} else {
			throw new GRidException('Code format invalid.  Unencoded GRids must contain 17 alphanumeric characters and encoded GRids must contain 18');
		}

		if (!preg_match('/^A1/', $this->code)) {
			throw new GRidException('Code format invalid.  GRid identifier scheme element must be A1');
		}
	}
	
	/**
	 * Helper function to validate the check character 
	 * on a given code in GRid standard format.
	 *
	 * @param string $code 
	 *
	 * @throws GRidException
	 * @throws InvalidArgumentException
	 *
	 * @return bool True if valid, false if invalid
	 */
	public static function checkGRid(string $code): bool {
		$grid = new GRid($code);
		return $grid->validateCheckChar();
	}

}
