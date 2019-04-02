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
class Mod3736 extends Mod
{
	/**
	 * Generate a check character for the given string
	 * or the class's current code.
	 *
	 * @param string $code Unencoded string
	 *
	 * @return string Generated check character
	 */
	public function generateCheckChar(?string $code = NULL): string {
		if (!$code) {
			$code = $this->code;
		}
		$p = 36;
		for($j = 0; $j < mb_strlen($code); $j++) {
			$a = self::convertCharVal($code[$j]);
			$s = ($p % 37) + $a;
			$p = ($s % 36 ?: 36) * 2;
		}
		$x = 37 - ($p % 37);
		if ($x == 36) $x = 0;
		return self::convertCharVal($x);
	}
}
