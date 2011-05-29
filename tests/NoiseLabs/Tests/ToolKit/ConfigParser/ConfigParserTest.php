<?php
/**
 * @category NoiseLabs
 * @package ToolKit
 * @version 0.1.0
 * @author Vítor Brandão <noisebleed@noiselabs.org>
 * @copyright (C) 2011 Vítor Brandão <noisebleed@noiselabs.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NoiseLabs\Tests\ToolKit\ConfigParser;

use NoiseLabs\ToolKit\ConfigParser\ConfigParser;

class ConfigParserTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @expectedException NoiseLabs\ToolKit\ConfigParser\Exception\DuplicateSectionException
	 */
	public function testAddDuplicateSection()
	{
		$section = 'github.com';

		$cfg = new ConfigParser();
		$cfg->read(__DIR__.'/Fixtures/source.cfg');

		$cfg->addSection($section);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testAddNonStringSection()
	{
		$section = array();

		$cfg = $this->create();
		$cfg->addSection($section);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testAddDefaultSection()
	{
		$section = 'DeFaulT';

		$cfg = $this->create();
		$cfg->addSection($section);
	}

	public function testHasSection()
	{
		$cfg = $this->create();

		$this->assertFalse($cfg->hasSection('non-existing-section'));

		$this->assertFalse($cfg->hasSection('default'));

		$this->assertTrue($cfg->hasSection('github.com'));
	}

	public function testHasOption()
	{
		$cfg = $this->create();

		$this->assertTrue($cfg->hasOption('github.com', 'User'));

		$this->assertFalse($cfg->hasOption('non-existing-section', 'User'));

		$this->assertFalse($cfg->hasOption('github.com', 'non-existing-option'));

		$this->assertTrue($cfg->hasOption(null, 'ForwardX11'));

		$this->assertTrue($cfg->hasOption('', 'ForwardX11'));

		$this->assertFalse($cfg->hasOption('', 'User'));
	}

	protected function create($file = null)
	{
		$cfg = new ConfigParser();

		if (isset($file)) {
			$cfg->read(__DIR__.'/Fixtures/'.$file);
		}
		else {
			$cfg->read(__DIR__.'/Fixtures/source.cfg');
		}

		return $cfg;
	}
}

?>