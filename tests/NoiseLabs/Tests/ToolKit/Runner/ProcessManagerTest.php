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
 * @author Vítor Brandão <vitor@noiselabs.org>
 * @copyright (C) 2011 Vítor Brandão <vitor@noiselabs.org>
 * @license http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL-3
 * @link http://www.noiselabs.org
 * @since 0.2.0
 */

namespace NoiseLabs\Tests\ToolKit\Runner;

use NoiseLabs\ToolKit\Runner\Process;
use NoiseLabs\ToolKit\Runner\ProcessManager;

class ProcessManagerTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->pm = new ProcessManager();
    }

    /**
     * @covers NoiseLabs\ToolKit\Runner\ProcessManager::has
     */
    public function testHas()
    {
        $this->pm->add('free', new Process('free'));

        $this->assertTrue($this->pm->has('free'));
        $this->assertFalse($this->pm->has('uptime'));
    }

    /**
     * @covers NoiseLabs\ToolKit\Runner\ProcessManager::add
     */
    public function testAdd()
    {
        $free = new Process('free');
        $uptime = new Process('uptime');

        $this->pm->
            add('free', $free)->
            add('uptime', $uptime);

        $this->assertEquals($free, $this->pm->get('free'));
        $this->assertEquals($uptime, $this->pm->get('uptime'));
    }

    /**
     * @covers NoiseLabs\ToolKit\Runner\ProcessManager::set
     */
    public function testSet()
    {
        $free = new Process('free');

        $self = $this->pm->set('free', $free);

        $this->assertEquals($free, $this->pm->get('free'));
        $this->assertEquals($this->pm, $self);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetWithNonStringId()
    {
        $this->pm->set(array(), new Process('uptime'));
    }

    /**
     * @covers NoiseLabs\ToolKit\Runner\ProcessManager::remove
     */
    public function testRemove()
    {
        $this->pm->set('uptime', new Process('uptime'));

        $this->assertTrue($this->pm->has('uptime'));

        $this->pm->remove('uptime');

        $this->assertFalse($this->pm->has('uptime'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testRemoveWithNonExistingId()
    {
        $this->pm->remove('non-existing-id');
    }

    public function testCount()
    {
        $this->assertEquals(0, count($this->pm));

        $this->pm->
            add('free', new Process('free'))->
            add('uptime', new Process('uptime'));

        $this->assertEquals(2, count($this->pm));
    }

    public function testSettings()
    {
        $this->pm->settings->set('cwd','manager');

        $free = new Process('free');
        $free->settings->set('cwd', 'process');
        $this->assertEquals('process', $free->settings->get('cwd'));

        // check $free has now 'manager' as the 'cwd' setting instead of
        // 'process'
        $this->pm->add('free', $free);
        $this->assertEquals('manager', $this->pm->get('free')->settings->get('cwd'));
    }
}

?>
