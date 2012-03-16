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
 * The Polyline class defines a linear overlay of connected line segments on the
 * map. A Polyline object consists of an array of LatLng locations, and creates
 * a series of line segments that connect those locations in an ordered
 * sequence.
 *
 * @since 0.2.0
 * @author Vítor Brandão <noisebleed@noiselabs.org>
 */
class Polyline extends BaseOverlay
{
    const OVERLAY_TYPE 	= 'Polyline';

    public $markers = array();

    /**
     * Polylines are drawn as a series of straight segments on the map. You can
     * specify custom colors, weights, and opacities for the stroke of the line
     * within a Polyline options object used when constructing your line, or
     * change those properties after construction. A polyline supports the
     * following stroke styles:
     * - strokeColor specifies a hexadecimal HTML color of the format "#FFFFFF".
     * The Polyline class does not support named colors.
     * - strokeOpacity specifies a numerical fractional value between 0.0 and
     * 1.0 (default) of the opacity of the line's color.
     * - strokeWeight specifies the weight of the line's stroke in pixels.
     *
     * @since 0.2.0
     */
    protected function getDefaultOptions()
    {
        return array(
            'strokeColor'		=> '#FF0000',
            'strokeOpacity'		=> 1.0,
            'strokeWeight'		=> 2,
            'randomStrokeColor'	=> false
        );
    }

    /**
     * A simple method to calculate a random color for the polyline segments.
     * This method is called when option 'randomStrokeColor' is set to TRUE.
     *
     * An hex color between #000000 and #ffffff is returned.
     *
     * @since 0.2.0
     */
    protected function getRandomColor()
    {
        return '#'.substr('00000' . dechex(mt_rand(0, 0xffffff)), -6);
    }

    public static function create(array $markers = array(), array $options = array())
    {
        $polyline = new static();

        $polyline->markers = $markers;

        $polyline->options->add($options);

        /**
         * Calculate a random color for the line, if requested.
         */
        if ($polyline->options->get('randomStrokeColor') === true) {
            $polyline->options->set('strokeColor', $polyline->getRandomColor());
        }

        return $polyline;
    }

    /**
     * @since 0.2.0
     */
    public function hasMarkers()
    {
        if (!empty($this->markers)) {
            return true;
        }

        return false;
    }

    /**
     * @since 0.2.0
     */
    public function hasMarker(Marker $marker)
    {
        return in_array($marker, $this->markers, true);
    }

    /**
     * @since 0.2.0
     */
    public function addMarker(Marker $marker, $focus = false)
    {
        $this->markers[] = $marker;
    }

    /**
     * @since 0.2.0
     */
    public function removeMarker(Marker $marker)
    {
        if (!$this->hasMarker($marker)) {
            return null;
        }

        unset($this->markers[array_search($marker, $this->markers, true)]);

        return $marker;
    }

    /**
     * @since 0.2.0
     */
    public function setMarkers(array $markers)
    {
        $this->markers = $markers;
    }

    public function getMarkers()
    {
        return $this->markers;
    }

    public function buildJavascriptOutput($map_object, $array_prefix,
    $array_sufix, $array_index)
    {
        $output = '';
        $js_class = ucfirst(self::OVERLAY_TYPE);
        $array_name = $array_prefix.$array_sufix;

          $output .=
        "\t// ".$js_class." ".$array_index."\n".
        "\t".$array_name."[".$array_index."] = new google.maps.".$js_class."({\n".
          "\t\tpath: [";
          foreach (array_keys($this->markers) as $k) {
              $output .=
            "\n\t\t\tnew google.maps.LatLng(".$this->markers[$k]->getLatitude().", ".$this->markers[$k]->getLongitude().")";
              if ($k+1 < count($this->markers)) {
                  $output .= ",";
              }
              else {
                  $output .= "\n";
              }
          }
          $output .=
          "\t\t],\n".
        "\t\tstrokeColor: \"".$this->options->get('strokeColor')."\",\n".
        "\t\tstrokeOpacity: ".$this->options->get('strokeOpacity').",\n".
        "\t\tstrokeWeight: ".$this->options->get('strokeWeight')."\n".
        "\t});\n".
        "\t".$array_name."[".$array_index."].setMap(".$map_object.");\n";

          return $output;
    }
}

?>
