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
 * @author 		Vítor Brandão <vitor@noiselabs.org>
 * @copyright 	(C) 2011 Vítor Brandão <vitor@noiselabs.org>
 * @license 	http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL-3
 * @link 		http://www.noiselabs.org
 * @since 		0.2.0
 */

namespace NoiseLabs\ToolKit\GoogleAPI\Maps\Overlay\Collection;

use NoiseLabs\ToolKit\GoogleAPI\Maps\Overlay\Collection\OverlayCollection;

/**
 * @author 		Vítor Brandão <vitor@noiselabs.org>
 * @since 		0.2.0
 */
class MarkerCollection extends OverlayCollection
{
    public $prefix = 'markers';
    public $dependents = array('InfoWindow', 'Icon', 'Shape', 'Shadow');

    public function declareJavascriptVariables()
    {
        $output = "\tvar ".$this->prefix.$this->sufix." = [];\n";

        foreach ($this->dependents as $var) {
            $output .= "\tvar ".$this->prefix.$var.$this->sufix." = [];\n";
        }

        return $output;
    }
}

