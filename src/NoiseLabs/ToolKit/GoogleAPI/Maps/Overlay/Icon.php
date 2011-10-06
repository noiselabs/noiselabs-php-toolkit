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
 * @since 		0.2.0
 */

namespace NoiseLabs\ToolKit\GoogleAPI\Maps\Overlay;

use NoiseLabs\ToolKit\GoogleAPI\Maps\Overlay\BaseOverlay;

/**
 * Markers may define an icon to show in place of the default icon. Defining an
 * icon involves setting a number of properties that define the visual behavior
 * of the marker.
 *
 * Simple Icons:
 * In the most basic case, an icon can simply indicate an image to use instead
 * of the default Google Maps pushpin icon by setting the marker's icon property
 * to the URL of an image. The Google Maps API will size the icon automatically
 * in this case.
 *
 * Complex Icons:
 * More complex icons will want to specify complex shapes (which indicate
 * regions that are clickable), add shadow images, and specify the "stack order"
 * of how they should display relative to other overlays.
 * Shadow images should generally be created at a 45 degree angle (upward and to
 * the right) from the main image, and the bottom left corner of the shadow
 * image should align with the bottom-left corner of the icon image. Shadow
 * images should be 24-bit PNG images with alpha transparency so that image
 * boundaries appear correctly on the map.
 *
 * Please note that when dealing with "complex icons" the GoogleMaps Javascript
 * API require the usage of a MarkerImage class but this PHP library uses the
 * same Icon class for both "simple" and "complex" icons.
 *
 * @author Vítor Brandão <noisebleed@noiselabs.org>
 * @since 0.2.0
 */
class Icon extends BaseOverlay
{
	public $image;
	protected $shadow;
	protected $shape;

	protected function getDefaultOptions()
	{
		return array();
	}

	/**
	 *
	 * @param unknown_type $image
	 *
	 * @since 0.2.0
	 */
	public static function create($image)
	{
		$icon = new static();

		$icon->image = $image;

		return $icon;
	}

	public function buildJavascriptOutput($js_map_variable, $js_array_name, $js_array_index)
	{
		return '';
	}
}

?>