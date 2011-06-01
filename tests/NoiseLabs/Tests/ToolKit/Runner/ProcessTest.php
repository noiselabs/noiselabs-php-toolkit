<?php
/**
 * This file is part of NoiseLabs-PHP-ToolKit
 *
 * NoiseLabs-PHP-ToolKit is free software; you can redistribute it
 * and/or modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * NoiseLabs-PHP-ToolKit is distributed in the hope that it will be
 * useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with NoiseLabs-PHP-ToolKit; if not, see
 * <http://www.gnu.org/licenses/>.
 *
 * Copyright (C) 2011 Vítor Brandão
 *
 * @category NoiseLabs
 * @package Runner
 * @author Vítor Brandão <noisebleed@noiselabs.org>
 * @copyright (C) 2011 Vítor Brandão <noisebleed@noiselabs.org>
 * @license http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL-3
 * @link http://www.noiselabs.org
 * @since 0.2.0
 */

namespace NoiseLabs\Tests\ToolKit\Runner;

use NoiseLabs\ToolKit\Runner\Process;

class ProcessTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->app = __DIR__.'/Fixtures/app.php';
	}

	public function testSettings()
	{
		$settings = array(
						'cwd' 			=> '/tmp',
						'new-setting' 	=> 'new-value'
						);

		$proc = new Process('free', $settings);

		$this->assertEquals('/tmp', $proc->settings->get('cwd'));
		$this->assertEquals('new-value', $proc->settings->get('new-setting'));

		$wd = '/var/tmp';
		$proc->settings->set('cwd', $wd);
		$this->assertEquals($wd, $proc->settings->get('cwd'));
	}

	public function testBeforeRun()
	{
		$proc = new Process('free');

		$this->assertEquals(null, $proc->getReturnCode());
		$this->assertEquals(null, $proc->getOutput());
		$this->assertEquals(null, $proc->getErrorMessage());
		$this->assertEquals('free', $proc->getCommand());
	}

	/**
     * @covers NoiseLabs\ToolKit\Runner\Process::run
     */
	public function testRun()
	{
		$proc = $this->runApp();

		$this->assertEquals('testing string', $proc->getOutput());
		$this->assertEquals(254, $proc->getReturnCode());
		$this->assertEquals('these are errors', $proc->getErrorMessage());
	}

	protected function runApp()
	{
		$proc = new Process('php '.$this->app);
		$proc->run();

		return $proc;
	}

	/**
     * @covers NoiseLabs\ToolKit\Runner\Process::setCommand
     */
	public function testSetCommand()
	{
		$proc = new Process('free');
		$this->assertEquals('free', $proc->getCommand());

		$proc->setCommand('uptime');
		$this->assertEquals('uptime', $proc->getCommand());
	}

	/**
     * @covers NoiseLabs\ToolKit\Runner\Process::addArguments
     */
	public function testAddArguments()
	{
		$proc = new Process('free');
		$this->assertEquals('free', $proc->getCommand());

		$proc->addArguments('-m -l');
		$this->assertEquals('free -m -l', $proc->getCommand());

		$this->assertEquals($proc, $proc->addArguments('--help'));
	}

	/**
     * @covers NoiseLabs\ToolKit\Runner\Process::replaceArguments
     */
	public function testReplaceArguments()
	{
		$proc = new Process('/usr/bin/free -m -l');
		$this->assertEquals('/usr/bin/free -m -l', $proc->getCommand());

		$proc->replaceArguments('--help');
		$this->assertEquals('/usr/bin/free --help', $proc->getCommand());
	}
}

?>