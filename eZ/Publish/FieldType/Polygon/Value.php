<?php
/**
 * File containing the Polygon FieldType Value class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace Donfelice\PolygonFieldTypeBundle\eZ\Publish\FieldType\Polygon;

use eZ\Publish\Core\FieldType\Value as BaseValue;

class Value extends BaseValue
{

    /**
     * Text content.
     *
     * @var string
     */
    //public $text;
    public $polygon;
    public $geojson;

    //Contructor
    public function __construct( $arg = [] )
    {
        if ( !is_array( $arg ) ) {
            $arg = ['polygon' => $arg];
        }

        parent::__construct( $arg );
    }

    //Methods of the class Value
    public function __toString()
    {
        return (string)$this->polygon;
    }

}
