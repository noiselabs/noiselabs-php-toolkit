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
 * @category 	NoiseLabs
 * @package 	GoogleAPI
 * @author 		Vítor Brandão <noisebleed@noiselabs.org>
 * @copyright 	(C) 2011 Vítor Brandão <noisebleed@noiselabs.org>
 * @license 	http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL-3
 * @link 		http://www.noiselabs.org
 * @since 		0.1.0
 */

namespace NoiseLabs\ToolKit\GoogleAPI\Maps\Overlay;

use NoiseLabs\ToolKit\GoogleAPI\ParameterBag;
use NoiseLabs\ToolKit\GoogleAPI\Maps\Geolocation;
use NoiseLabs\ToolKit\GoogleAPI\Maps\Overlay\BaseOverlay;
use NoiseLabs\ToolKit\GoogleAPI\Maps\Overlay\InfoWindow;

/**
 * Markers identify locations on the map. By default, they use a standard icon,
 * though you can set a custom icon within the marker's constructor or by
 * calling setIcon() on the marker.
 *
 * @author Vítor Brandão <noisebleed@noiselabs.org>
 * @since 0.1.0
 */
class Marker extends BaseOverlay
{
	const OVERLAY_TYPE = 'Marker';
	protected $geolocation;
	protected $icon;
	protected $infowindow;

	protected function getDefaultOptions()
	{
		return array(
			'animation'	=> false,
			'title'		=> "Unnamed marker",
			'uiEvent'	=> 'click'
		);
	}

    public static function create($latitude, $longitude, array $options = array(),
    Icon $icon = null, InfoWindow $infowindow = null)
    {
		$marker = new static();

		$marker->geolocation = Geolocation::create($latitude, $longitude);
		$marker->icon = $icon;
		$marker->infowindow = $infowindow;

		$marker->options->add($options);

		return $marker;
	}

	/**
	 * @since 0.2.0
	 */
	public function hasIcon()
	{
		return isset($this->icon) && ($this->icon instanceof Icon);
	}

	/**
	 *
	 * @param Icon $icon
	 *
	 * @since 0.2.0
	 */
	public function setIcon(Icon $icon)
	{
		$this->icon = $icon;
	}

	/**
	 * @since 0.2.0
	 */
	public function getIcon()
	{
		return $this->icon;
	}

	/**
	 * @since 0.2.0
	 */
	public function hasInfoWindow()
	{
		return isset($this->infowindow) && ($this->infowindow instanceof InfoWindow);
	}

	/**
	 *
	 * @param InfoWindow $infowindow
	 *
	 * @since 0.2.0
	 */
	public function setInfoWindow(InfoWindow $infowindow)
	{
		$this->infowindow = $infowindow;
	}

	/**
	 * @since 0.2.0
	 */
	public function getInfoWindow()
	{
		return $this->infowindow;
	}

	/**
	 * Saves the latitude.
	 *
	 * @param mixed $latitude
	 *
	 * @since 0.1.0
	 */
	public function setLatitude($latitude)
	{
		$this->geolocation->latitude = (float) $latitude;
	}

	/**
	 * @since 0.1.0
	 */
	public function getLatitude()
	{
		return $this->geolocation->latitude;
	}

	/**
	 *
	 * @param unknown_type $longitude
	 *
	 * @since 0.1.0
	 */
	public function setLongitude($longitude)
	{
		$this->geolocation->longitude = (float) $longitude;
	}

	/**
	 * @since 0.1.0
	 */
	public function getLongitude()
	{
		return $this->geolocation->longitude;
	}

	public function buildJavascriptOutput($map_object, $array_prefix,
	$array_sufix, $array_index)
	{
		$output = '';
		$js_class = ucfirst(self::OVERLAY_TYPE);
		$marker_array = $array_prefix.$array_sufix;
		$infowindow_array = $array_prefix.'InfoWindow'.$array_sufix;

		$output .=
		"\t// ".$js_class." ".$array_index."\n".
		"\t".$marker_array."[".$array_index."] = new google.maps.".$js_class."({\n".
		"		position: new google.maps.LatLng(".$this->getLatitude().", ".$this->getLongitude()."),\n".
		"		map: ".$map_object;

		if ($this->hasIcon()) {
			$this->icon->buildJavascriptOutput($map_object, null, null, $array_index);
		}
		if ($this->options->has('title')) {
			$output .= ",\n\t\ttitle: '".$this->options->get('title')."'";
		}
		$output .= "\n\t});\n";

		// Info windows
		if ($this->hasInfoWindow())
		{
			$output .= $this->infowindow->buildJavascriptOutput(
				$map_object, $array_prefix.'InfoWindow', $array_sufix, $array_index
			);

			$output .= "\tgoogle.maps.event.addListener(".$marker_array."[".$array_index."], '".$this->options->get('uiEvent')."', function() {\n".
			"\t\t".$marker_array."[".$array_index."].open(".$map_object.", markersArray[$array_index]);\n".
			"\t});\n";

		}
		$output .= "\tbounds.extend(".$marker_array."[".$array_index."].getPosition());\n";

		return $output;
	}
}

?>