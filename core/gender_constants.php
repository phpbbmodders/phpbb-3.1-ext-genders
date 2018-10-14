<?php
/**
*
* Genders extension for the phpBB Forum Software package.
*
* @copyright 2016 Rich McGirr (RMcGirr83)
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace rmcgirr83\genders\core;

class gender_constants
{
	// generates a list of font awesome graphics to use
	// add to or subtract from to suit your needs.
	public static function getGenderChoices()
	{
		return array(
			'GENDER_NONE'	=> 0,
			'FA-MARS' => 1,
			'FA-VENUS' => 2,
			'FA-INTERSEX' => 3,
			'FA-MARS-DOUBLE' => 4,
			'FA-MARS-STROKE' => 5,
			'FA-MARS-STROKE-H' => 6,
			'FA-MARS-STROKE-V' => 7,
			'FA-MERCURY' => 8,
			'FA-NEUTER' => 9,
			'FA-TRANSGENDER' => 10,
			'FA-TRANSGENDER-ALT' => 11,
			'FA-VENUS-DOUBLE' => 12,
			'FA-VENUS-MARS' => 13,
			'FA-GENDERLESS' => 14,
		);
	}
}
