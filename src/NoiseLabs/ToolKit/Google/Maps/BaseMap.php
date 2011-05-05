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

use NoiseLabs\ToolKit\ParameterBag;
use NoiseLabs\ToolKit\Google\Maps\MapInterface;
use NoiseLabs\ToolKit\Google\Maps\Marker;

/**
 * GoogleMaps base class (abstract).
 *
 * Inspired by a GoogleMaps implementation made by tirnanog06.
 * @see https://github.com/kriswallsmith/GoogleBundle
 */
abstract class BaseMap implements MapInterface
{
	/**
	 * An unique ID to identify this map, useful when manipulating an array of
	 * maps. This is also used as the ID for the <div> element.
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * A collection of map markers. Each element of the array should be an
	 * object of type Marker.
	 */
	protected $markers = array();

	/**
	 * A set of parameters to append to configure how the Maps JavaScript API is
	 * loaded.
	 *
	 * @var \NoiseLabs\ToolKit\ParameterBag
	 */
	public $parameters;

	/**
	 * GoogleMaps configuration. This covers settings like zoom level to the
	 * usage of HTTPS.
	 *
	 * @var \NoiseLabs\ToolKit\ParameterBag
	 */
	public $options;

	public $https = false;

	/**
	 *
	 */
	public function __construct($id = 'map', array $options = array(), array $parameters = array())
	{
		$this->id = $id;

		// option defaults
		$this->options = new ParameterBag($this->getOptionsDefaults());
		$this->options->add($options);

		// parameter defaults
		$this->parameters = new ParameterBag(array(
			'sensor'	=> 'false'
			));
		$this->parameters->add($parameters);
	}

	protected function getOptionsDefaults()
	{
		$options = array();

		/**
		 * Zoom level. Ranges from 0 (less zoom) to 18 (higher zoom).
		 *
		 * Offering a map of the entire Earth as a single image would either
		 * require an immense map, or a small map with very low resolution.
		 * As a result, map images within Google Maps and the Maps API are
		 * broken up into map "tiles" and "zoom levels." At low zoom levels, a
		 * small set of map tiles covers a wide area; at higher zoom levels,
		 * the tiles are of higher resolution and cover a smaller area.
		 *
		 * You specify the resolution at which to display a map by setting the
		 * Map's zoom property, where zoom 0 corresponds to a map of the Earth
		 * fully zoomed out, and higher zoom levels zoom in at a higher
		 * resolution.
		 */
		$options['zoom'] = 12;

		/**
		 * Map type. The following types are supported:
		 * - 'ROADMAP' 		Displays the normal, default 2D tiles of Google Maps.
		 * - 'SATELLITE'	Displays photographic tiles.
		 * - 'HYBRID' 		Displays a mix of photographic tiles and a tile layer
		 *					for prominent features (roads, city names).
		 * - 'TERRAIN' 		Displays physical relief tiles for displaying elevation
		 *					and water features (mountains, rivers, etc.).
		 */
		$options['type'] = 'ROADMAP';

		$options['width'] = '400px';
		$options['height'] = '400px';

		$options['https'] = false;	// use https?

		return $options;
	}

	public function setId($id)
	{
		$this->id = (string) $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function hasMarkers()
	{
		if (!empty($this->markers)) {
			return true;
		}
		return false;
	}

	public function hasMarker(Marker $marker)
	{
		return in_array($marker, $this->markers, true);
	}

	public function addMarker(Marker $marker)
	{
		$this->markers[] = $marker;
	}

	public function removeMarker(Marker $marker)
	{
		if (!$this->hasMarker($marker)) {
			return null;
		}

		unset($this->markers[array_search($marker, $this->markers, true)]);
		return $marker;
	}

	public function setMarkers(array $markers)
	{
		$this->markers = $markers;
	}

	public function getMarkers()
	{
		return $this->markers;
	}

	public static function create()
	{
		$map = new static();

		return $map;
	}
}

?>