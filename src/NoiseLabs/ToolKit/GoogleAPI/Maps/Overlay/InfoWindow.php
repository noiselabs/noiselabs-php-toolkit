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

use NoiseLabs\ToolKit\GoogleAPI\Maps\Overlay\BaseOverlay;

/**
 * InfoWindows displays content in a floating window above the map. The info
 * window looks a little like a comic-book word balloon; it has a content area
 * and a tapered stem, where the tip of the stem is at a specified location on
 * the map. You can see the info window in action by clicking business markers
 * on Google Maps.
 *
 * @author Vítor Brandão <noisebleed@noiselabs.org>
 * @since 0.2.0
 */
class InfoWindow extends BaseOverlay
{
	const OVERLAY_TYPE = 'InfoWindow';

	/**
	 * Content to display in the InfoWindow. This can be an HTML element, a
	 * plain-text string, or a string containing HTML. The InfoWindow will be
	 * sized according to the content. To set an explicit size for the content,
	 * set content to be a HTML element with that size.
	 *
	 * @var string
	 */
	public $content;

	/**
	 * Available options:
	 *
	 * disableAutoPan: Disable auto-pan on open. By default, the info window
	 * will pan the map so that it is fully visible when it opens.
	 *
	 * maxWidth: Maximum width of the infowindow, regardless of content's width.
	 * This value is only considered if it is set before a call to open. To
	 * change the maximum width when changing content, call close, setOptions,
	 * and then open.
	 *
	 * pixelOffset: The offset, in pixels, of the tip of the info window
	 * from the point on the map at whose geographical coordinates the info
	 * window is anchored. If an InfoWindow is opened with an anchor,
	 * the pixelOffset will be calculated from the top-center of the anchor's
	 * bounds.
	 *
	 * position: The LatLng at which to display this InfoWindow. If the
	 * InfoWindow is opened with an anchor, the anchor's position will be used
	 * instead.
	 *
	 * zIndex: All InfoWindows are displayed on the map in order of their
	 * zIndex, with higher values displaying in front of InfoWindows with lower
	 * values. By default, InfoWinodws are displayed according to their
	 * latitude, with InfoWindows of lower latitudes appearing in front of
	 * InfoWindows at higher latitudes. InfoWindows are always displayed in
	 * front of markers.
	 *
	 * uiEvent: User event used to trigger the opening of the info window.
	 * Defaults to 'click'. Other event types may be used like:
	 * 'dbclick', 'mouseup', 'mousedown', 'mouseover' or 'mousedown'.
	 */
	protected function getDefaultOptions()
	{
		return array(
				'disableAutoPan'	=> false,
				'pixelOffset'		=> 0,
				'zIndex'			=> 0,
				'uiEvent'			=> 'click'
		);
	}

	public function buildJavascriptOutput($js_map_variable,
	$js_array_name, $js_array_index)
	{
		return
		"\t".$js_array_name."[$js_array_index] = new google.maps.".static::OVERLAY_TYPE."({\n".
		"\t\tcontent: '".$this->content."'\n".
		"\t});\n";
	}



	public static function create($content)
	{
		$infowindow = new static();

		$infowindow->content = $content;

		return $infowindow;
	}
}