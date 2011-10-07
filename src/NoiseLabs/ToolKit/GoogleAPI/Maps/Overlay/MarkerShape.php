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
 * This object defines the marker shape to use in determination of a marker's
 * clickable region. The shape consists of two properties — type and coord —
 * which define the general type of marker and coordinates specific to that type
 * of marker.
 *
 * @author 		Vítor Brandão <noisebleed@noiselabs.org>
 * @since		0.2.0
 */
class MarkerShape extends BaseOverlay
{
	/**
	 * The format of this attribute depends on the value of the type and follows
	 * the w3 AREA coords specification found at
	 * http://www.w3.org/TR/REC-html40/struct/objects.html#adef-coords.
	 * The coords attribute is an array of integers that specify the pixel
	 * position of the shape relative to the top-left corner of the target
	 * image. The coordinates depend on the value of type as follows:
	 *  - circle: coords is [x1,y1,r] where x1,y2 are the coordinates of the
	 * center of the circle, and r is the radius of the circle.
	 *  - poly: coords is [x1,y1,x2,y2...xn,yn] where each x,y pair contains the
	 * coordinates of one vertex of the polygon.
	 *  - rect: coords is [x1,y1,x2,y2] where x1,y1 are the coordinates of the
	 * upper-left corner of the rectangle and x2,y2 are the coordinates of the
	 * lower-right coordinates of the rectangle.
	 *
	 * @var array
	 */
	public $coord = array();


	/**
	 * Describes the shape's type and can be circle, poly or rect.
	 *
	 * @var string
	 *
	 * @see http://code.google.com/intl/pt-PT/apis/maps/documentation/javascript/overlays.html#ComplexIcons
	 */
	public $type = 'poly';

	public function buildJavascriptOutput($map_object, $array_prefix,
	$array_sufix, $array_index)
	{
		$array_name = $array_prefix.$array_sufix;

		return $array_name."[".$array_index."] = {\n".
		"coord: [".implode(', ', $this->coord)."],\n".
		"type: '".$this->type."'\n".
		"};\n";
	}
}

?>