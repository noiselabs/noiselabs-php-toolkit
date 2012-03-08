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
 * A structure representing a Marker icon or shadow image.
 *
 * @see			http://code.google.com/intl/pt-PT/apis/maps/documentation/javascript/reference.html#MarkerImage
 * @author 		Vítor Brandão <noisebleed@noiselabs.org>
 * @since 		0.2.0
 */
class MarkerImage extends BaseOverlay
{
	public $url;
	public $size = array(0, 0);
	public $origin = array(0, 0);
	public $anchor = array(0, 0);

	/**
	 *
	 * @param unknown_type $map_object
	 * @param unknown_type $array_prefix
	 * @param unknown_type $array_sufix
	 * @param unknown_type $array_index
	 *
	 * @since 0.2.0
	 *
	 * @see http://code.google.com/intl/pt-PT/apis/maps/documentation/javascript/overlays.html#ComplexIcons
	 */
	public function buildJavascriptOutput($map_object, $array_prefix,
	$array_sufix, $array_index)
	{
		$array_name = $array_prefix.$array_sufix;

		return $array_name."[".$array_index."] = new google.maps.MarkerImage('".$this->url."',\n".
			"new google.maps.Size(".$this->size[0].", ".$this->size[1]."),\n".
			"new google.maps.Point(".$this->origin[0].", ".$this->origin[1]."),\n".
			"new google.maps.Point(".$this->anchor[0].", ".$this->anchor[1].")\n".
			");\n";
	}
}

?>