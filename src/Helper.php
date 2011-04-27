<?php
/**
 * @category noiselabs.org
 * @package noiselabs-php-toolkit
 * @copyright (C) 2011 Vítor Brandão <noisebleed@noiselabs.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
	 * @param string $dangerous_filename The source filename to be "sanitized"
	 * @param string $platform The target OS
	 *
	 * @return Boolean string A safe version of the input filename
	 */
	public static function sanitizeFileName($dangerous_filename, $platform = 'Unix')
	{
		if (in_array(strtolower($platform), array('unix', 'linux')) {
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
}

?>