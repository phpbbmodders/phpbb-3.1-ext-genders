<?php
/**
*
* @package - Genders language
* @copyright (c) 2015 phpBBmodders.net
* @author bonelifer (William Jacoby) bonelifer@phpbbmodders.net
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
	'GENDER'			=> 'Gender',
	'GENDER_NONE'	=> 'None Specified',
	'FA-MARS' => 'Male',
	'FA-VENUS' => 'Female',
	'FA-INTERSEX' => 'Intersex',
	'FA-MARS-DOUBLE' => 'Mars Double',
	'FA-MARS-STROKE' => 'Mars Stroke',
	'FA-MARS-STROKE-H' => 'Mars Stroke H',
	'FA-MARS-STROKE-V' => 'Mars Stroke V',
	'FA-MERCURY' => 'Mercury',
	'FA-NEUTER' => 'Neuter',
	'FA-TRANSGENDER' => 'Transgender',
	'FA-TRANSGENDER-ALT' => 'Transgender Alt',
	'FA-VENUS-DOUBLE' => 'Venus Double',
	'FA-VENUS-MARS' => 'Venus Mars',
	'FA-GENDERLESS' => 'Genderless',
	'TOO_LARGE_USER_GENDER'	=> 'Gender value is too large.',
	'TOO_SMALL_USER_GENDER'	=> 'Gender value is too small.',
));
