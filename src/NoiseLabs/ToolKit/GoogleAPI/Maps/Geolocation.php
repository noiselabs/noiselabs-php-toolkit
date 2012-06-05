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

namespace NoiseLabs\ToolKit\GoogleAPI\Maps;

use NoiseLabs\ToolKit\Helper;

/**
 * The core class for each Overlay element containing GPS coordinates (latitude
 * and longitude) to identify a specific position.
 *
 * @author Vítor Brandão <noisebleed@noiselabs.org>
 * @since 0.2.0
 */
class Geolocation
{
    public $latitude;
    public $longitude;
    public $altitude;
    public $speed;
    public $timestamp;

    /**
     * @param array $properties
     *
     * @since 0.2.0
     */
    public function __construct(array $properties = array())
    {
         foreach ($properties as $k => $v) {
              $this->$k = $v;
          }
    }

    /**
     *
     * @param float  $latitude
     * @param float  $longitude
     * @param float  $altitude
     * @param float  $speed
     * @param string $timestamp
     */
    public static function create($latitude, $longitude, $altitude = null,
    $speed = null, $timestamp = null)
    {
        if (!isset($timestamp)) {
            $timestamp = Helper::timestamp();
        }

        $geolocation = new static(array(
                        'latitude'	=> $latitude,
                        'longitude'	=> $longitude,
                        'altitude'	=> $altitude,
                        'speed'		=> $speed,
                        'timestamp'	=> $timestamp
        ));

        return $geolocation;
    }
}

