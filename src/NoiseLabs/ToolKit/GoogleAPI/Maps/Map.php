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
 * @since 		0.1.0
 */

namespace NoiseLabs\ToolKit\GoogleAPI\Maps;

use NoiseLabs\ToolKit\GoogleAPI\Maps\BaseMap;

/**
 * A class that makes use of the Google Maps API to create customizable maps
 * that can be embedded on your website.
 *
 * The API version in use is Google Maps Javascript API Version 3.
 * @link http://code.google.com/intl/pt-PT/apis/maps/documentation/javascript/basics.html
 */
class Map extends BaseMap
{
    /**
     * The GoogleMaps Javascript API version supported in this PHP library.
     * @var string
     */
    const GOOGLE_MAPS_JAVASCRIPT_API = 'v3';

    /**
     * For backwards compatibility.
     */
    public function printGoogleJS()
    {
        return $this->loadJavascriptApi();
    }

    /**
     * Include the Maps JavaScript API using a script tag.
     *
     * This function should be called in between the html <head></head> tags.
     */
    public function loadJavascriptApi()
    {
        $parameters = $this->parameters->all();
        $html = '<script type="text/javascript" src="';

        /**
         * Load via HTTPS?
         *
         * Loading the Google Maps Javascript API V3 over HTTPS allows your
         * application to use the Maps API within pages that are secured using
         * HTTPS: the HTTP over the Secure Socket Layer (SSL) protocol.
         *
         * Loading the Maps API over HTTPS is necessary in SSL applications to
         * avoid security warnings in most browsers, and is recommended for
         * applications that include sensitive user data, such as a user's
         * location, in requests.
         */
        if (true === $this->options->get('https', false)) {
            $html .= 'https://maps-api-ssl.google.com/maps/api/js?v=3';
        }
        else {
            $html .= 'http://maps.google.com/maps/api/js';
            if (!empty($parameters)) { $html .= '?'; }
        }

        // include defined parameters like 'sensor' or 'language' (if available)
        foreach ($parameters as $key => $value) {
            $html .= '&'.$key.'='.$value;
        }
        $html = rtrim($html, '&'); // remove the last ampersand ('orphan')
        $html .= '"></script>';

        echo $html;
    }

    /**
     * The method called to output the HTML and JavaScript code used to create
     * a GoogleMap. Should be called in between HTML <body></body> tags.
     */
    public function render()
    {
        // Create a div element to hold the Map.
        echo
        "<div id=\"".$this->getId()."\" style=\"width:".
        $this->options->get('width')."; height:".$this->options->get('height').
        ";\"></div>\n";

        echo
        "<script type=\"text/javascript\">\n".
        "function show_googlemap_".$this->getId()."() {\n";

        // declare all required overlay variables
        foreach (array_keys($this->overlays) as $collection) {
            echo $this->overlays[$collection]->declareJavascriptVariables();
        }

        echo
        "\tvar bounds = new google.maps.LatLngBounds();\n";

        echo
        "\tvar mapOptions = {\n".
        "\t\tzoom: ".$this->options->get('zoom').",\n".
        "\t\tcenter: new google.maps.LatLng(0, 0),\n".
        "\t\tmapTypeId: google.maps.MapTypeId.".strtoupper($this->options->get('type'))."\n".
        "\t};\n".
        "\tvar map = new google.maps.Map(document.getElementById(\"".$this->getId()."\"), mapOptions);\n".
        "\n";

        foreach (array_keys($this->overlays) as $collection)
        {
            $iterator = $this->overlays[$collection]->getIterator();

            while ($iterator->valid()) {
                echo $iterator->current()->buildJavascriptOutput('map', $this->overlays[$collection]->prefix, $this->overlays[$collection]->sufix, $iterator->key());
                $iterator->next();
                echo "\n";
            }
        }

        if ($this->hasFocus()) {
            echo
            "\tmap.setCenter(new google.maps.LatLng(".$this->options->get('focus')->latitude.", ".$this->options->get('focus')->longitude."));\n";
        }
        else {
            // Auto-center and auto-zoom
            echo
            "\tmap.fitBounds(bounds);\n".
            "\tmap.setCenter(bounds.getCenter());\n";
        }

        echo
        "}\n".
        "\n".
        "window.onload = show_googlemap_".$this->getId().";\n".
        "</script>\n";
    }
}

?>
