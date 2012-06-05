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
 * Copyright (C) 2011 Vítor Brandão <noisebleed@noiselabs.org>
 *
 *
 * @category NoiseLabs
 * @package Process
 * @version 0.1.1
 * @author Vítor Brandão <noisebleed@noiselabs.org>
 * @copyright (C) 2011 Vítor Brandão <noisebleed@noiselabs.org>
 */

namespace NoiseLabs\ToolKit\Runner;

use NoiseLabs\ToolKit\Runner\ParameterBag;
use NoiseLabs\ToolKit\Runner\ProcessInterface;
use NoiseLabs\ToolKit\Runner\ProcessManagerInterface;

class ProcessManager implements ProcessManagerInterface
{
    protected $processes = array();
    public $settings;

    public function __construct(array $settings = array())
    {
        $this->settings = new ParameterBag($settings);
    }

    public function has($id)
    {
        return isset($this->processes[$id]);
    }

    public function add($id, ProcessInterface $process)
    {
        if ($this->has($id)) {
            throw new \InvalidArgumentException(sprintf('A process with ID "%s" already exists.', $id));
        }

        return $this->set($id, $process);
    }

    /**
     * Add a new Process to the container.
     * A previous process with the same ID will be overridden.
     *
     * @param string $id Process ID
     * @param NoiseLabs\ToolKit\Runner\ProcessInterface The Process object to
     * add
     *
     * @return self (ProcessManager)
     */
    public function set($id, ProcessInterface $process)
    {
        if (!is_string($id)) {
            throw new \InvalidArgumentException('$id must be a string');
        }

        $this->processes[$id] = $process;
        // Copy our own set of settings over Process current settings
        $this->processes[$id]->settings->add($this->settings->all());

        return $this;
    }

    /**
     * Gets a Process registered in this object.
     *
     * @param $id Process ID
     *
     * @throws InvalidArgumentException if no process is registered with $id
     */
    public function get($id)
    {
        if ($this->has($id)) {
            return $this->processes[$id];
        } else {
            throw new \InvalidArgumentException(sprintf('Process ID "%s" does not exist.', $id));
        }
    }

    public function remove($id)
    {
        if ($this->has($id)) {
            unset($this->processes[$id]);
        } else {
            throw new \InvalidArgumentException(sprintf('Process ID "%s" does not exist.', $id));
        }
    }

    /**
     * Returns true if the section exists (implements the \ArrayAccess
     * interface).
     *
     * @param string $offset The name of the section
     *
     * @return Boolean true if the section exists, false otherwise
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * Returns the array of options associated with the section (implements
     * the \ArrayAccess interface).
     *
     * @param string $offset The offset of the value to get
     *
     * @return mixed The array of options associated with the section
     */
    public function offsetGet($offset)
    {
        return $this->has($offset) ? $this->get($offset) : null;
    }

    /**
     * Adds an array of options to the given section (implements the
     * \ArrayAccess interface).
     *
     * @param string $offset Process ID
     * @param array  $values Process to register
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Removes the Process with the given ID (implements the
     * \ArrayAccess interface).
     *
     * @param string $name The name of the Process to be removed
     */
    public function offsetUnset($name)
    {
        $this->has($name) && $this->remove($name);
    }

    /**
     * Returns the number of registered processes (implements the \Countable
     * interface).
     *
     * @return integer The number of sections
     */
    public function count()
    {
        return count($this->processes);
    }

    /**
     * Returns the iterator for this group.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->processes);
    }
}

