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

class Marker
{
	protected $latitude;
    protected $longitude;
    protected $icon = false;

	public function __construct()
	{

	}

    public static function create($latitude, $longitude)
    {
		$marker = new static();
		$marker->setLatitude($latitude);
		$marker->setLongitude($longitude);

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

