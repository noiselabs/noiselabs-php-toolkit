<?php
/**
 * @category NoiseLabs
 * @package ToolKit
 *
 * Phoogle Maps 2.0 | Uses Google Maps API to create customizable maps
 * that can be embedded on your website
 *    Copyright (C) 2005  Justin Johnson
 *
 *    This program is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation; either version 2 of the License, or
 *    (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this program; if not, write to the Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * Phoogle Maps 2.0
 * Uses Google Maps Mapping API to create customizable Google Maps that can be
 * embedded on your website
 *
 * @author       Justin Johnson <justinjohnson@system7designs.com>
 * @copyright    2005 system7designs
 */

namespace NoiseLabs\ToolKit\GoogleAPI;

/**
 * Uses Google Maps API to create customizable maps that can be embedded on your
 * website.
 *
 * This class is a rewrite of the original PhoogleMap class created by Justin
 * Johnson (system7designs).
 *
 * @author Justin Johnson <justinjohnson@system7designs.com>
 * @author Vítor Brandão <noisebleed@noiselabs.org>
 *
 * @deprecated GoogleMap class is deprecated and marked for removal once 0.2.0
 * becomes stable. Please avoid usage.
 */
class GoogleMap
{
    const LOCALHOST_API_KEY = 'ABQIAAAAdi_0xCw-nuskZRWE2Z6PBhT2yXp_ZAY8_ufC3CFXhHIE1NvwkxR3tBq3ZXP3CHmGs13Ec_yYzjqSfA';

    /**
     * validPoints : array
     * Holds addresses and HTML Messages for points that are valid (ie: have longitude and latitutde)
     */
    public $validPoints = array();

    /**
     * invalidPoints : array
     * Holds addresses and HTML Messages for points that are invalid (ie: don't have longitude and latitutde)
     */
    public $invalidPoints = array();

    /**
     * mapWidth
     * width of the Google Map, in pixels
     */
    public $mapWidth = 300;

    /**
     * mapHeight
     * height of the Google Map, in pixels
     */
    public $mapHeight = 300;

    /**
     * apiKey
     * Google API Key
     */
    public $apiKey = '';

    /**
     * showControl
     * True/False whether to show map controls or not
     */
    public $showControl = true;

    /**
     * showType
     * True/False whether to show map type controls or not
     */
    public $showType = true;

    /**
     * controlType
     * string: can be 'small' or 'large'
     * display's either the large or the small controls on the map, small by default
     */
    public $controlType = 'small';

    /**
     * zoomLevel
     * int: 0-17
     * set's the initial zoom level of the map
     */
    public $zoomLevel = 4;

    /**
     *
     */
    public static function create(array $options = array())
    {
        $map = new static();
        $map->setAPIKey(static::LOCALHOST_API_KEY);

        return $map;
    }

    /**
     * Add's an address to be displayed on the Google Map using latitude/longitude
     * early version of this function, considered experimental.
     */
    public function addGeoPoint($lat, $long, $infoHTML)
    {
        $pointer = count($this->validPoints);
        $this->validPoints[$pointer]['lat'] = $lat;
        $this->validPoints[$pointer]['long'] = $long;
        $this->validPoints[$pointer]['htmlMessage'] = $infoHTML;
    }

    /**
     * Center's Google Map on a specific point (thus eliminating the need for
     * two different show methods from version 1.0).
     */
    public function centerMap($lat, $long)
    {
        $this->centerMap = "map.centerAndZoom(new GPoint(".$long.",".$lat."), ".$this->zoomLevel.");\n";
    }

    /**
     * Add's an address to be displayed on the Google Map (thus eliminating the
     * need for two different show methods from version 1.0).
     *
     * @param	$address:string
     * @return Boolean True:False (True if address has long/lat, false if it
     *			doesn't)
     */
    public function addAddress($address,$htmlMessage=null)
    {
        if (!is_string($address)) {
            die("All Addresses must be passed as a string");
        }
        $apiURL = "http://maps.google.com/maps/geo?&output=xml&key=ABQIAAAAJUqzkGTwxHyfr_AFlet6FRSqiy-aFjcz-2DijvkBG8XEujeXPxSyYk3YLvgZJq0HyHrB2gCTJJRVAg&q=";
        $addressData = file_get_contents($apiURL.urlencode($address));

        $results = $this->xml2array($addressData);
        if (empty($results['Point']['coordinates'])) {
            $pointer = count($this->invalidPoints);
            $this->invalidPoints[$pointer]['lat']= $results['Point']['coordinates'][0];
            $this->invalidPoints[$pointer]['long']= $results['Point']['coordinates'][1];
            $this->invalidPoints[$pointer]['passedAddress'] = $address;
            $this->invalidPoints[$pointer]['htmlMessage'] = $htmlMessage;
          } else {
            $pointer = count($this->validPoints);
            $this->validPoints[$pointer]['lat']= $results['Point']['coordinates'];
            $this->validPoints[$pointer]['long']= $results['Point']['coordinates'];
            $this->validPoints[$pointer]['passedAddress'] = $address;
            $this->validPoints[$pointer]['htmlMessage'] = $htmlMessage;
        }
    }

    /**
     * Displays either a table or a list of the address points that are valid.
     * Mainly used for debugging but could be useful for showing a list of
     * addresses on the map.
     *
     * @param	$displayType:string
     * @param	$css_id:string
     * @return nothing
     */
    public function showValidPoints($displayType,$css_id)
    {
        $total = count($this->validPoints);
        if ($displayType == "table") {
            echo "<table id=\"".$css_id."\">\n<tr>\n\t<td>Address</td>\n</tr>\n";
        for ($t=0; $t<$total; $t++) {
            echo"<tr>\n\t<td>".$this->validPoints[$t]['passedAddress']."</td>\n</tr>\n";
        }
        echo "</table>\n";
        }
        if ($displayType == "list") {
            echo "<ul id=\"".$css_id."\">\n";
        for ($lst=0; $lst<$total; $lst++) {
            echo "<li>".$this->validPoints[$lst]['passedAddress']."</li>\n";
        }
        echo "</ul>\n";
        }
    }

    /**
     * Displays either a table or a list of the address points that are invalid.
     * Mainly used for debugging shows only the points that are NOT on the map.
     *
     * @param	$displayType:string
     * @param	$css_id:string
     * @return nothing
     */
    public function showInvalidPoints($displayType,$css_id)
    {
        $total = count($this->invalidPoints);
        if ($displayType == "table") {
            echo "<table id=\"".$css_id."\">\n<tr>\n\t<td>Address</td>\n</tr>\n";
            for ($t=0; $t<$total; $t++) {
                echo"<tr>\n\t<td>".$this->invalidPoints[$t]['passedAddress']."</td>\n</tr>\n";
            }
        echo "</table>\n";
        }
        if ($displayType == "list") {
            echo "<ul id=\"".$css_id."\">\n";
            for ($lst=0; $lst<$total; $lst++) {
                echo "<li>".$this->invalidPoints[$lst]['passedAddress']."</li>\n";
            }
            echo "</ul>\n";
        }
    }

    /**
     * Sets the width of the map to be displayed.
     *
     * @param	$width:int
     * @return nothing
     */
    public function setWidth($width)
    {
        $this->mapWidth = $width;
    }

    /**
     * Sets the height of the map to be displayed
     *
     * @param	$height:int
     * @return nothing
     */
    public function setHeight($height)
    {
        $this->mapHeight = $height;
    }

    /**
     * Stores the API Key acquired from Google.
     *
     * @param	$key:string
     * @return nothing
     */
    public function setAPIkey($key)
    {
        $this->apiKey = $key;
    }

    /**
     * Adds the necessary Javascript for the Google Map to function should be
     * called in between the html <head></head> tags.
     *
     * @return othing
     */
    public function printGoogleJS()
    {
        echo "\n<script src=\"http://maps.google.com/maps?file=api&v=2&key=".$this->apiKey."\" type=\"text/javascript\"></script>\n";
    }

    /**
     * Displays the Google Map on the page.
     */
    public function showMap()
    {
        echo "\n<div id=\"map\" style=\"width: ".$this->mapWidth."px; height: ".$this->mapHeight."px\">\n</div>\n";
        echo "    <script type=\"text/javascript\">\n
        function showmap()
        {
                //<![CDATA[\n
            if (GBrowserIsCompatible()) {\n
            var map = new GMap(document.getElementById(\"map\"));\n";
            if (empty($this->centerMap)) {
                echo "map.centerAndZoom(new GPoint(".$this->validPoints[0]['long'].",".$this->validPoints[0]['lat']."), ".$this->zoomLevel.");\n";
            } else {
                echo $this->centerMap;
            }
            echo "}\n
            var icon = new GIcon();
            icon.image = \"http://labs.google.com/ridefinder/images/mm_20_red.png\";
            icon.shadow = \"http://labs.google.com/ridefinder/images/mm_20_shadow.png\";
            icon.iconSize = new GSize(12, 20);
            icon.shadowSize = new GSize(22, 20);
            icon.iconAnchor = new GPoint(6, 20);
            icon.infoWindowAnchor = new GPoint(5, 1);
            ";
        if ($this->showControl) {
            if ($this->controlType == 'small') { echo "map.addControl(new GSmallMapControl());\n"; }
            if ($this->controlType == 'large') { echo "map.addControl(new GLargeMapControl());\n"; }
        }
        if ($this->showType) {
            echo "map.addControl(new GMapTypeControl());\n";
        }

        $numPoints = count($this->validPoints);
        for ($g = 0; $g<$numPoints; $g++) {
            echo "var point".$g." = new GPoint(".$this->validPoints[$g]['long'].",".$this->validPoints[$g]['lat'].")\n;
            var marker".$g." = new GMarker(point".$g.");\n
            map.addOverlay(marker".$g.")\n
            GEvent.addListener(marker".$g.", \"click\", function() {\n";
            if ($this->validPoints[$g]['htmlMessage']!=null) {
                echo "marker".$g.".openInfoWindowHtml(\"".$this->validPoints[$g]['htmlMessage']."\");\n";
            } else {
                echo "marker".$g.".openInfoWindowHtml(\"".$this->validPoints[$g]['passedAddress']."\");\n";
            }
            echo "});\n";
        }
        echo "    	//]]>\n
        }
        window.onload = showmap;
        </script>\n";
        }

    /***************************************************************************
     * THIS BLOCK OF CODE IS FROM Roger Veciana's CLASS (assoc_array2xml)
     * OBTAINED FROM PHPCLASSES.ORG
     **************************************************************************/

    public function xml2array($xml)
       {
        $this->depth=-1;
        $this->xml_parser = xml_parser_create();
        xml_set_object($this->xml_parser, $this);
        xml_parser_set_option ($this->xml_parser,XML_OPTION_CASE_FOLDING,0);//Don't put tags uppercase
        xml_set_element_handler($this->xml_parser, "startElement", "endElement");
        xml_set_character_data_handler($this->xml_parser,"characterData");
        xml_parse($this->xml_parser,$xml,true);
        xml_parser_free($this->xml_parser);

        return $this->arrays[3];

    }

    public function startElement($parser, $name, $attrs)
    {
           $this->keys[]=$name;
           $this->node_flag=1;
           $this->depth++;
     }

    public function characterData($parser,$data)
    {
       $key=end($this->keys);
       $this->arrays[$this->depth][$key]=$data;
       $this->node_flag=0;
    }

    public function endElement($parser, $name)
    {
       $key=array_pop($this->keys);
       if ($this->node_flag==1) {
         $this->arrays[$this->depth][$key]=$this->arrays[$this->depth+1];
         unset($this->arrays[$this->depth+1]);
        }
       $this->node_flag=1;
       $this->depth--;
     }

    /*** END CODE FROM Roger Veciana's CLASS (assoc_array2xml) ****************/
}

