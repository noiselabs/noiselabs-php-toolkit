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
 * @category NoiseLabs
 * @package GoogleAPI
 * @author Vítor Brandão <noisebleed@noiselabs.org>
 * @copyright (C) 2011 Vítor Brandão <noisebleed@noiselabs.org>
 * @license http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL-3
 * @link http://www.noiselabs.org
 * @since 0.2.0
 */

namespace NoiseLabs\ToolKit\GoogleAPI\Maps\Overlay;

use NoiseLabs\ToolKit\GoogleAPI\ParameterBag;

/**
 * Overlays are objects on the map that are tied to latitude/longitude
 * coordinates, so they move when you drag or zoom the map. Overlays reflect
 * objects that you "add" to the map to designate points, lines, areas, or
 * collections of objects.
 *
 * The Maps API has several types of overlays:
 * - Single locations on the map are displayed using markers. Markers may
 * sometimes display custom icon images, in which case they are usually referred
 * to as "icons." Markers and icons are objects of type Marker.
 * - Lines on the map are displayed using polylines (representing an ordered
 * sequence of locations). Lines are objects of type Polyline.
 * - Areas of arbitrary shape on the map are displayed using polygons, which are
 * similar to polylines. Like polylines, polygons are an ordered sequence of
 * locations; unlike polylines, polygons define a region which they enclose.
 * - Map layers may be displayed using overlay map types. You can create your
 * own set of tiles by creating custom map types which either replace base map
 * tile sets, or display on top of existing base map tile sets as overlays.
 * - The info window is also a special kind of overlay for displaying content
 * (usually text or images) within a popup balloon on top of a map at a given
 * location.
 *
 * This class provides the base class (abstract) for each overlay type listed
 * above.
 *
 * @author Vítor Brandão <noisebleed@noiselabs.org>
 * @since 0.2.0
 */
abstract class BaseOverlay implements OverlayInterface
{
	public $options;

	public function __construct()
	{
		$this->options = new ParameterBag($this->getDefaultOptions());
	}

	abstract protected function getDefaultOptions();

	abstract public function buildJavascriptOutput($map_object, $array_prefix,
	$array_sufix, $array_index);
}

?>