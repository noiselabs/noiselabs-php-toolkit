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
 * @license http://opensource.org/licenses/LGPL-3.0 LGPL-3.0
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

	/**
     * @covers NoiseLabs\ToolKit\Runner\Process::run
     */
	public function testRun()
	{

	}

	public function testAddArguments()
	{
		$proc = new Process('free');

		$this->assertEquals('free', $proc->getCommand());

		$proc->addArguments('-m -l');

		$this->assertEquals('free -m -l', $proc->getCommand());
	}

	public function testReplaceArguments()
	{
		$proc = new Process('/usr/bin/free -m -l');

		$this->assertEquals('/usr/bin/free -m -l', $proc->getCommand());

		$proc->replaceArguments('--help');

		$this->assertEquals('/usr/bin/free --help', $proc->getCommand());
	}
}

?>