<?php
/**
*
* @package Genders
* @copyright (c) 2015 Rich Mcgirr (RMcGirr83)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace rmcgirr83\genders\migrations;

class m2_initial_data extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['genders_version']) && version_compare($this->config['genders_version'], '1.0.0', '>=');
	}

	static public function depends_on()
	{
		return array('\rmcgirr83\genders\migrations\m1_initial_schema');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('genders_version', '1.0.0')),
		);
	}
}
