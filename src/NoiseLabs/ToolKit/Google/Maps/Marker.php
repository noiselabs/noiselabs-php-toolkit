<?php
/**
 * @category NoiseLabs
 * @package Google
 * @copyright (C) 2011 Vítor Brandão <noisebleed@noiselabs.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NoiseLabs\ToolKit\Google\Maps;

use NoiseLabs\Toolkit\ParameterBag;

class Marker
{
	protected $latitude;
    protected $longitude;

    /**
	 * Marker options.
	 *
	 * Known keys:
	 *  - icon: 	An icon to show in place of the default icon
	 *  - title:
	 *
	 * @var \NoiseLabs\ToolKit\ParameterBag
	 */
	public $options;

	public function __construct()
	{
		$this->options = new ParameterBag();
	}

    public static function create($latitude, $longitude, array $options = array())
    {
		$marker = new static();
		$marker->setLatitude($latitude);
		$marker->setLongitude($longitude);
		$marker->options->add($options);

		return $marker;
	}

	public function setLatitude($latitude)
	{
		$this->latitude = (float) $latitude;
	}

	public function getLatitude()
	{
		return $this->latitude;
	}

	public function setLongitude($longitude)
	{
		$this->longitude = (float) $longitude;
	}

	public function getLongitude()
	{
		return $this->longitude;
	}
}

?>

