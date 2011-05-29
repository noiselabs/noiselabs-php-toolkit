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
	protected $filenames;
	protected $out_filename;
	
	protected function getFilename()
	{
		return $this->filenames[0];
	}
	
	protected function setUp()
	{
		$this->cfg = new ConfigParser();
		
		$this->filenames = array(
					__DIR__.'/Fixtures/source.cfg'
					);
		
		$this->out_filename = tempnam(sys_get_temp_dir(), str_replace('\\', '_',__CLASS__).'_');
	}
	
	protected function tearDown()
	{
		file_exists($this->out_filename) && unlink($this->out_filename);
	}
	
	/**
	 * @expectedException NoiseLabs\ToolKit\ConfigParser\Exception\DuplicateSectionException
	 */
	public function testAddDuplicateSection()
	{
		$section = 'github.com';

		$this->cfg->read($this->getFilename());
		
		$this->cfg->addSection($section);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testAddNonStringSection()
	{
		$section = array();

		$this->cfg->read($this->getFilename());
		
		$this->cfg->addSection($section);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testAddDefaultSection()
	{
		$section = 'DeFaulT';

		$this->cfg->read($this->getFilename());
		
		$this->cfg->addSection($section);
	}

	public function testHasSection()
	{
		$this->cfg->read($this->getFilename());

		$this->assertFalse($this->cfg->hasSection('non-existing-section'));

		$this->assertFalse($this->cfg->hasSection('default'));

		$this->assertTrue($this->cfg->hasSection('github.com'));
	}

	public function testHasOption()
	{
		$this->cfg->read($this->getFilename());

		$this->assertTrue($this->cfg->hasOption('github.com', 'User'));

		$this->assertFalse($this->cfg->hasOption('non-existing-section', 'User'));

		$this->assertFalse($this->cfg->hasOption('github.com', 'non-existing-option'));

		$this->assertTrue($this->cfg->hasOption(null, 'ForwardX11'));

		$this->assertTrue($this->cfg->hasOption('', 'ForwardX11'));

		$this->assertFalse($this->cfg->hasOption('', 'User'));
	}
}

?>