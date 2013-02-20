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
 * @author 		Vítor Brandão <vitor@noiselabs.org>
 * @copyright 	(C) 2011 Vítor Brandão <vitor@noiselabs.org>
 * @license 	http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL-3
 * @link 		http://www.noiselabs.org
 * @since 		0.1.0
 */

namespace NoiseLabs\ToolKit\GoogleAPI\Maps;

use NoiseLabs\ToolKit\GoogleAPI\ParameterBag;
use NoiseLabs\ToolKit\GoogleAPI\Maps\MapInterface;
use NoiseLabs\ToolKit\GoogleAPI\Maps\Overlay\Collection\OverlayCollectionFactory;
use NoiseLabs\ToolKit\GoogleAPI\Maps\Overlay\OverlayInterface;

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
     * Array holding all overlays added to this map instance. Each first order
     * array element is of type OverlayCollection.
     *
     * @since 0.2.0
     */
    protected $overlays = array();

    /**
     * Supported overlay types.
     *
     * @since 0.2.0
     */
    protected $_overlay_types = array('InfoWindow', 'Marker', 'Polyline');

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
        $options['default_zoom'] = 12;

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

        $options['center'] = 0; // center map on desidered Marker (array index)

        /**
         * Focus map (center and zoom) on a given marker? Value is array index.
         */
        $options['focus'] = false;
        $options['sensor'] = 'false';

        return $options;
    }

    /**
     * Sets the map ID.
     *
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = (string) $id;
    }

    /**
     * @return The map ID.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * A method to add overlays (Markers, Polylines, etc.) to this map object.
     *
     * For each overlay type an OverlayCollection object is created to hold
     * OverlayInterface objects.
     *
     * @param  OverlayInterface         $overlay
     * @throws InvalidArgumentException if the overlay type is not supported.
     *
     * @since 0.2.0
     */
    public function addOverlay(OverlayInterface $overlay)
    {
        $overlay_type = $overlay::OVERLAY_TYPE;

         // create a new *Collection object to hold overlay objects if it
         // doesn't exist yet.
        if (!isset($this->overlays[$overlay_type])) {
            // only overlay types defined in $this->_overlay_types are allowed
            if (!in_array($overlay_type, $this->_overlay_types)) {
                throw new \InvalidArgumentException("Overlay type '".
                $overlay_type."' is not supported. Supported types are: ".
                implode(', ', $this->_overlay_types).".");
            }

            $this->overlays[$overlay_type] = OverlayCollectionFactory::create($overlay_type);
        }

        $this->overlays[$overlay_type]->append($overlay);
    }

    /**
     * @param string $type
     *
     * @since 0.2.0
     */
    public function getOverlays($type = null)
    {
        return (isset($type)) ? $this->overlays[$type] : $this->overlays;
    }

    /**
     * @param string $type
     *
     * @since 0.2.0
     */
    public function hasOverlays($type = null)
    {
        return isset($type) ? !empty($this->overlays[$type]) : !empty($this->overlays);
    }

    /**
     * @return An array containing all overlay types added to the map.
     *
     * @since 0.2.0
     */
    public function getOverlayTypes()
    {
        return array_keys($this->overlays);
    }

    /**
     * @todo Please check if this method is still required after the
     * introduction of the OverlayCollection object.
     *
     * @since 0.2.0
     */
    public function getOverlayClasses()
    {
        $classes = array();

        foreach (array_keys($this->overlays) as $type) {
            $classes[] = get_class(current($this->overlays[$type]));
        }

        return $classes;
    }

    /**
     * @since 0.2.0
     */
    public function hasFocus()
    {
        return (false !== $this->options->get('focus'));
    }

    /**
     * @param $geolocation Geolocation object to retrieve GPS coordinate from.
     * @param $zoom Zoom level to apply
     *
     * @since 0.2.0
     */
    public function setFocus(Geolocation $geolocation, $zoom = 16)
    {
        $this->options->set('zoom', $zoom);
        $this->options->set('focus', $geolocation);
    }

    /**
     * @since 0.2.0
     */
    public function clearFocus()
    {
        $this->options->set('zoom', $this->options->get('default_zoom'));
        $this->options->set('focus', false);
    }

    public static function create()
    {
        $map = new static();

        return $map;
    }
}

