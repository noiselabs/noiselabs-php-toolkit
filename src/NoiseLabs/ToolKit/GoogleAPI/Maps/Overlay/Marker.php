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
 * @since 0.1.0
 */

namespace NoiseLabs\ToolKit\GoogleAPI\Maps\Overlay;

use NoiseLabs\ToolKit\GoogleAPI\ParameterBag;
use NoiseLabs\ToolKit\GoogleAPI\Maps\Geolocation;
use NoiseLabs\ToolKit\GoogleAPI\Maps\Overlay\BaseOverlay;

class Marker extends BaseOverlay
{
	const OVERLAY_TYPE = 'marker';
	protected $geolocation;

	/**
	 * Marker options.
	 *
	 * Known keys:
	 *  - animation: TODO
	 *  - icon: 	 An icon to show in place of the default icon.
	 *  - title:	 Marker title shown as tooltip.
	 *
	 *  @since 0.2.0
	 */
	protected function getDefaultOptions()
	{
		return array(
			'animation'	=> false,
			'icon'		=> false,
			'title'		=> "Unnamed marker"
		);
	}

    public static function create($latitude, $longitude, array $options = array())
    {
		$marker = new static(Geolocation::create($latitude, $longitude));

		$marker->options->add($options);

		return $marker;
	}

	public function setLatitude($latitude)
	{
		$this->geolocation->latitude = (float) $latitude;
	}

	public function getLatitude()
	{
		return $this->geolocation->latitude;
	}

	public function setLongitude($longitude)
	{
		$this->geolocation->longitude = (float) $longitude;
	}

	public function getLongitude()
	{
		return $this->geolocation->longitude;
	}

	public function buildJavascriptOutput($js_map_variable, $js_array_name, $js_array_index)
	{
		;
	}
}

?>