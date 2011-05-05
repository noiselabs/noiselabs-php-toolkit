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

use NoiseLabs\ToolKit\Google\Maps\Marker;

interface MapInterface
{
	public function setId($id);
	public function getId();
	public function hasMarkers();
	public function hasMarker(Marker $marker);
	public function setMarkers(array $markers);
	public function getMarkers();
	public function addMarker(Marker $marker);
	public function removeMarker(Marker $marker);
	public function render();
}

?>