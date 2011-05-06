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

use NoiseLabs\ToolKit\Google\Maps\BaseMap;

/**
 * A class that makes use of the Google Maps API to create customizable maps
 * that can be embedded on your website.
 *
 * The API version in use is Google Maps Javascript API Version 3.
 * @link http://code.google.com/intl/pt-PT/apis/maps/documentation/javascript/basics.html
 */
class Map extends BaseMap
{
	/**
	 * For backwards compatibility.
	 */
	public function printGoogleJS()
	{
		return $this->loadJavascriptApi();
	}

	/**
	 * Include the Maps API JavaScript using a script tag.
	 *
	 * This function should be called in between the html <head></head> tags.
	 */
	public function loadJavascriptApi()
	{
		$parameters = $this->parameters->all();
		$html = '<script type="text/javascript" src="';

		/**
		 * Load via HTTPS?
		 *
		 * Loading the Google Maps Javascript API V3 over HTTPS allows your
		 * application to use the Maps API within pages that are secured using
		 * HTTPS: the HTTP over the Secure Socket Layer (SSL) protocol.
		 *
		 * Loading the Maps API over HTTPS is necessary in SSL applications to
		 * avoid security warnings in most browsers, and is recommended for
		 * applications that include sensitive user data, such as a user's
		 * location, in requests.
		 */
		if (true === $this->options->get('https', false)) {
			$html .= 'https://maps-api-ssl.google.com/maps/api/js?v=3';
		}
		else {
			$html .= 'http://maps.google.com/maps/api/js';
			if (!empty($parameters)) { $html .= '?'; }
		}

		// include defined parameters like 'sensor' or 'language' (if available)
		foreach ($parameters as $key => $value) {
			$html .= $key.'='.$value.'&';
		}
		$html = rtrim($html, '&'); // remove the last ampersand ('orphan')
		$html .= '"></script>';

		echo $html;
	}

	public function render()
	{
		//var_dump($this->getMarkers());die;

		// Create a div element to hold the Map.
		echo "<div id=\"".$this->getId()."\" style=\"width:".$this->options->get('width')."; height:".$this->options->get('height')."\"></div>\n";

		// GoogleMaps won't work without at least one location.
		if (!$this->hasMarkers()) {
			return;
		}
		else {
			$markers = $this->getMarkers();
		}

		echo
		"<script type=\"text/javascript\">\n".
		"function showmap() {\n".
		"	var markersArray = [];\n".
		"	var bounds = new google.maps.LatLngBounds();\n".
		"	var infowindowsArray = [];\n".
		"\n";

		// Create the map object
		$kc = $this->options->get('center');
		echo
		"	var mapOptions = {\n".
		"		zoom: ".$this->options->get('zoom').",\n".
		"		center: new google.maps.LatLng(".$markers[$kc]->getLatitude().", ".$markers[$kc]->getLongitude()."),\n".
		"		mapTypeId: google.maps.MapTypeId.".strtoupper($this->options->get('type'))."\n".
		"	};\n".
		"	var map = new google.maps.Map(document.getElementById(\"".$this->getId()."\"), mapOptions);\n".
		"\n";

		// Insert markers
		foreach (array_keys($markers) as $k) {
			echo
			"	// Marker $k\n".
			"	markersArray[$k] = new google.maps.Marker({\n".
			"		position: new google.maps.LatLng(".$markers[$k]->getLatitude().", ".$markers[$k]->getLongitude()."),\n".
			"		map: map";
			if ($markers[$k]->options->has('icon')) {
				echo ",\n		icon: '".$markers[$k]->options->get('icon')."'";
			}
			if ($markers[$k]->options->has('title')) {
				echo ",\n		title: '".$markers[$k]->options->get('title')."'";
			}
			echo
			"\n	});\n";
			// Info windows
			if ($markers[$k]->options->has('infowindow')) {
				echo
				"	infowindowsArray[$k] = new google.maps.InfoWindow({\n".
				"		content: '".$markers[$k]->options->get('infowindow')."'\n".
				"	});\n".
				"	google.maps.event.addListener(markersArray[$k], 'click', function() {\n".
				"		infowindowsArray[$k].open(map, markersArray[$k]);\n".
				"	});\n";
			}
			echo "	bounds.extend(markersArray[$k].getPosition());\n";
		}
		// Auto-center and auto-zoom
		echo
		"	map.fitBounds(bounds);\n".
		"	map.setCenter(bounds.getCenter());\n".
		"}\n".
		"window.onload = showmap;\n".
		"</script>\n";
	}
}

?>