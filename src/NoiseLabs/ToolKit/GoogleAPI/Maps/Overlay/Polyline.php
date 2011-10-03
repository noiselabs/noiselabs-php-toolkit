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
 * @since 0.2.0-BETA2
 */

namespace NoiseLabs\ToolKit\GoogleAPI\Maps\Overlay;

/**
 * The Polyline class defines a linear overlay of connected line segments on the
 * map. A Polyline object consists of an array of LatLng locations, and creates
 * a series of line segments that connect those locations in an ordered
 * sequence.
 *
 * @since 0.2.0-BETA2
 * @author Vítor Brandão <noisebleed@noiselabs.org>
 *
 */
class Polyline
{
	protected $_markers;
	public $stroke_color;
	public $stroke_opacity;
	public $stroke_weight;
}

?>