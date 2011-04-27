<?php
/**
 * @category NoiseLabs
 * @package ToolKit
 * @copyright (C) 2011 Vítor Brandão <noisebleed@noiselabs.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NoiseLabs\ToolKit;

/**
 * Helper holds a collection of static methods, useful for generic purposes
 *
 * @author Vítor Brandão <noisebleed@noiselabs.org>
 */
class Helper
{
	/**
	 * Returns a safe filename, for a given platform (OS), by replacing all
	 * dangerous characters with an underscore.
	 *
	 * @since 0.1.0
	 *
	 * @param string $dangerous_filename The source filename to be "sanitized"
	 * @param string $platform The target OS
	 *
	 * @return Boolean string A safe version of the input filename
	 */
	public static function sanitizeFileName($dangerous_filename, $platform = 'Unix')
	{
		if (in_array(strtolower($platform), array('unix', 'linux'))) {
				// our list of "dangerous characters", add/remove characters if necessary
				$dangerous_characters = array(" ", '"', "'", "&", "/", "\\", "?", "#");
		}
		else {
				// no OS matched? return the original filename then...
				return $dangerous_filename;
		}

		// every forbidden character is replace by an underscore
		return str_replace($dangerous_characters, '_', $dangerous_filename);
	}

	/**
	 * Returns an array made from property values extracted from each object in
	 * the array of objects.
	 *
	 * @since 0.1.0
	 *
	 * @param array $objects The collection of objects holding the target property
	 * @param string $property Property name to collect data from
	 *
	 * @return array
	 */
	public static function buildArrayFromObjectsProperty(array $objects, $property)
	{
		$values = array();

		foreach ($objects as $object) {
			if (property_exists($object, $property)) {
				$values[] = $object->$property;
			}
		}

		return $values;
	}

	/**
	 * Returns an array made from values extracted the array of obects using
	 * the given method.
	 *
	 * @since 0.1.0
	 *
	 * @param array $objects The collection of objects holding the target method
	 * @param string $property Method name to ask for data
	 *
	 * @return array
	 */
	public static function buildArrayFromObjectsMethod(array $objects, $method)
	{
		$values = array();

		foreach ($objects as $object) {
			if (method_exists($object, $method)) {
				$values[] = $object->$method();
			}
		}

		return $values;
	}
}

?>