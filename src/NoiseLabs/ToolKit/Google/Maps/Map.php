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
		// Create a div element to hold the Map.
		echo '<div id="'.$this->getId().'" style="width:'.$this->options->get('width').'; height:'.$this->options->get('height').'"></div>';

		// GoogleMaps won't work without at least one location.
		if (!$this->hasMarkers()) {
			return;
		}

		echo '
		<script type="text/javascript">
		function showmap() {
		//<![CDATA[
			var locationsArray = [];
			var markersArray = [];';
		// insert markers
		foreach ($this->getMarkers() as $marker) {
			echo "\nlocationsArray.push(new google.maps.LatLng(".$marker->getLatitude().", ".$marker->getLongitude()."));";
		}
		echo '
			var mapOptions = {
				zoom: '.$this->options->get('zoom').',
				center: locationsArray[0],
				mapTypeId: google.maps.MapTypeId.'.strtoupper($this->options->get('type')).'
			};
			var map = new google.maps.Map(document.getElementById("'.$this->getId().'"), mapOptions);
		//]]>
		}
		window.onload = showmap;
		</script>';
	}
}

?>