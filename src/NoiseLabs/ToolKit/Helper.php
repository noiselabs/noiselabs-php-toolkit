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
 * Copyright (C) 2011 Vítor Brandão <noisebleed@noiselabs.org>
 *
 *
 * @category NoiseLabs
 * @package ToolKit
 * @version 0.1.1
 * @author Vítor Brandão <noisebleed@noiselabs.org>
 * @copyright (C) 2011 Vítor Brandão <noisebleed@noiselabs.org>
 */

namespace NoiseLabs\ToolKit;

/**
 * Helper holds a collection of static methods, useful for generic purposes
 *
 * @author Vítor Brandão <noisebleed@noiselabs.org>
 */
class Helper
{
    const DATETIME_FORMAT = 'Y-m-d G:i:s';

    /**
     * Returns current timestamp.
     *
     * @param string $timestamp
     *
     * @since 0.2.0
     */
    public static function timestamp($timestamp = null)
    {
        return date(static::DATETIME_FORMAT, isset($timestamp) ? $timestamp : time());
    }

    /**
     * Returns a safe filename, for a given platform (OS), by replacing all
     * dangerous characters with an underscore.
     *
     * @since 0.1.0
     *
     * @param string $dangerous_filename The source filename to be "sanitized"
     * @param string $platform           The target OS
     *
     * @return Boolean string A safe version of the input filename
     */
    public static function sanitizeFileName($dangerous_filename, $platform = 'unix')
    {
        if (in_array(strtolower($platform), array('unix', 'linux'))) {
                // our list of "dangerous characters", add/remove characters if necessary
                $dangerous_characters = array(" ", '"', "'", "&", "/", "\\", "?", "#");
        } else {
                // no OS matched? return the original filename then...
                return $dangerous_filename;
        }

        // every forbidden character is replace by an underscore
        return str_replace($dangerous_characters, '_', $dangerous_filename);
    }

    /**
     * Returns an array made from property values extracted from each object in
     * the array of objects.
     *
     * @since 0.1.0
     *
     * @param array  $objects  The collection of objects holding the target property
     * @param string $property Property name to collect data from
     *
     * @return array
     */
    public static function buildArrayFromObjectsProperty(array $objects, $property)
    {
        $values = array();

        foreach ($objects as $object) {
            if (property_exists($object, $property)) {
                $values[] = $object->$property;
            }
        }

        return $values;
    }

    /**
     * Returns an array made from values extracted the array of obects using
     * the given method.
     *
     * @since 0.1.0
     *
     * @param array  $objects  The collection of objects holding the target method
     * @param string $property Method name to ask for data
     *
     * @return array
     */
    public static function buildArrayFromObjectsMethod(array $objects, $method)
    {
        $values = array();

        foreach ($objects as $object) {
            if (method_exists($object, $method)) {
                $values[] = $object->$method();
            }
        }

        return $values;
    }

    /**
     * An implementation of PECL's http_negotiate_language as posted on
     * http://www.php.net/manual/en/function.http-negotiate-language.php by
     * Anonymous (03-Nov-2008 11:23).
     *
     * This function negotiates the clients preferred language based on its
     * Accept-Language HTTP header. The qualifier is recognized and languages
     * without qualifier are rated highest. The qualifier will be decreased by
     * 10% for partial matches (i.e. matching primary language).
     *
     * @since 0.1.0
     *
     * @param array $available_languages Array with language-tag-strings that
     * are available
     * @param string $default_language The language to pick if none available
     *
     * @return string Returns the negotiated language or the default language
     */
    public static function getPreferredLanguage(array $available_languages = array('en'), $default_language = 'en')
      {
        // All $available_languages values must be lowercase
        $available_languages = array_map('strtolower', $available_languages);

        if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return $default_language;
        } else {
            $http_accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        }

        /**
         * standard for HTTP_ACCEPT_LANGUAGE is defined under
         * http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4
         * pattern to find is therefore something like this:
         *    1#( language-range [ ";" "q" "=" qvalue ] )
         * where:
         *    language-range  = ( ( 1*8ALPHA *( "-" 1*8ALPHA ) ) | "*" )
         *    qvalue         = ( "0" [ "." 0*3DIGIT ] )
         *            | ( "1" [ "." 0*3("0") ] )
         */
        preg_match_all("/([[:alpha:]]{1,8})(-([[:alpha:]|-]{1,8}))?" .
                   "(\s*;\s*q\s*=\s*(1\.0{0,3}|0\.\d{0,3}))?\s*(,|$)/i",
                   $http_accept_language, $hits, PREG_SET_ORDER);

        // default language (in case of no hits) is the first in the array
        $bestlang = $available_languages[0];
        $bestqval = 0;

        foreach ($hits as $arr) {
            // read data from the array of this hit
            $langprefix = strtolower ($arr[1]);
            if (!empty($arr[3])) {
                $langrange = strtolower ($arr[3]);
                $language = $langprefix . "-" . $langrange;
            } else {
                $language = $langprefix;
            }

            $qvalue = 1.0;

            if (!empty($arr[5])) $qvalue = floatval($arr[5]);

            // find q-maximal language
            if (in_array($language,$available_languages) && ($qvalue > $bestqval)) {
                $bestlang = $language;
                $bestqval = $qvalue;
            }
            // if no direct hit, try the prefix only but decrease q-value by 10% (as http_negotiate_language does)
            elseif (in_array($langprefix,$available_languages) && (($qvalue*0.9) > $bestqval))
            {
                $bestlang = $langprefix;
                $bestqval = $qvalue*0.9;
            }
        }

        return $bestlang;
    }
}

