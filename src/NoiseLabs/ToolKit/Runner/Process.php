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

class Process implements ProcessInterface
{
	const PACKAGE = 'Runner';
	protected $command;
	protected $_resource;
	protected $_output = array();
	protected $_retcode;
	protected $_descriptorspec;

	/**
	 * Known settings:
	 *
	 *  'sudo':
	 *		If TRUE, prepend every command with 'sudo'.
	 *
	 *  'cwd':
	 *		The initial working dir for the command. This must be an absolute
	 *		directory path, or NULL if you want to use the default value (the
	 *		working dir of the current PHP process).
	 *
	 *  'env':
	 *		An array with the environment variables for the command that will
	 *		be run, or NULL to use the same environment as the current PHP
	 *		process.
	 *
	 * @var array
	 */
	protected $_defaults = array(
				'sudo'	=> false,
				'cwd'	=> null,
				'env'	=> null
				);

	public $settings;

	public function __construct($command, array $settings = array())
	{
		$this->setCommand($command);

		// default settings
		$this->settings = new ParameterBag($this->_defaults);
		$this->settings->add($settings);

		$this->_descriptorspec = array(
					0 => array('pipe', 'r'),
					1 => array('pipe', 'w'),
					2 => array('pipe', 'w')
					);
	}

	public function __toString()
	{
		return 'Return code: '.$this->getReturnCode();
	}

	public function getCommand()
	{
		return $this->command;
	}

	public function setCommand($command)
	{
		$this->command = escapeshellcmd($command);
	}

	protected function reset()
	{
		$this->_resource = false;
		$this->_output = array();
		$this->_retcode = null;
	}

	public function run()
	{
		$this->reset();

		// use sudo?
		$command = (true === $this->settings->get('sudo', $this->_defaults['sudo'])) ?
			'sudo '.$this->command : $this->command;

		// current working directory
		$cwd = $this->settings->get('cwd', $this->_defaults['cwd']);

		// environment variables
		$env = $this->settings->get('env', $this->_defaults['env']);

		$this->_resource = proc_open(
						$command,
						$this->_descriptorspec,
						$pipes,
						$cwd,
						$env);

		if (is_resource($this->_resource)) {
			// $pipes now looks like this:
			// 0 => writeable handle connected to child stdin
			// 1 => readable handle connected to child stdout
			fclose($pipes[0]);
			$this->_output['stdout'] = stream_get_contents($pipes[1]);
			fclose($pipes[1]);
			$this->_output['stderr'] = stream_get_contents($pipes[2]);
			fclose($pipes[2]);

			// It is important that you close any pipes before calling
			// proc_close in order to avoid a deadlock
			$this->_retcode = proc_close($this->_resource);

			$msg = sprintf("%s: executed '%s' (ReturnCode: %d",
					static::PACKAGE,
					$this->command,
					$this->getReturnCode()
					);
			if ($this->getErrorMessage() != null) {
				$msg .= sprintf(", Error: '%s'", $this->getErrorMessage());
			}
			$msg .= ")";

			$this->log($msg);
		}
		else {
			$this->log(static::PACKAGE.": failed to open resource using proc_open");
		}

		return $this;
	}

	public function exec()
	{
		return $this->run();
	}

	public function success()
	{
		return (0 === $this->getReturnCode());
	}

	public function getOutput()
	{
		return $this->_output['stdout'];
	}

	public function getErrorMessage()
	{
		return isset($this->_output['stderr']) ? trim($this->_output['stderr']) : null;
	}

	public function getReturnCode()
	{
		return $this->_retcode;
	}

	public function getName()
	{
		return basename(current(explode(' ', $this->command)));
	}

	public function log($message, $level = 'info')
	{
		error_log($message);
	}
}

?>