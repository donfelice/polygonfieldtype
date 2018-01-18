<?php

namespace Donfelice\PolygonFieldTypeBundle\eZ\Publish\FieldType\Polygon;

use eZ\Publish\Core\FieldType\FieldType;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
//use eZ\Publish\SPI\Persistence\Content\FieldValue;
use eZ\Publish\SPI\Persistence\Content\FieldValue as PersistenceValue;
use eZ\Publish\Core\FieldType\ValidationError;
use eZ\Publish\SPI\FieldType\Value as SPIValue;
use eZ\Publish\Core\FieldType\Value as BaseValue;
//use eZ\Publish\Core\FieldType\Value as CoreValue;

class Type extends FieldType
{

    /**
     * List of settings available for this FieldType.
     *
     * The key is the setting name, and the value is the default value for this setting
     *
     * @var array
     */
    protected $settingsSchema = array(
        // output as geojson, default is simple longlat pairs
        /*
        'options' => array(
            'type' => 'array',
            'default' => array(),
        ),
        */
        'geojson' => array(
            'type' => 'boolean',
            'default' => false,
        ),
    );

    public function getFieldTypeIdentifier()
    {
        return 'ezpolygon';
    }

    protected function createValueFromInput( $inputValue )
    {
        if ( is_string( $inputValue ) ) {
            $inputValue = new Value( ['polygon' => $inputValue] );
        }

        return $inputValue;
    }

    protected function checkValueStructure( BaseValue $value )
    {
        if ( !is_string( $value->polygon ) ) {
            throw new InvalidArgumentType(
                '$value->polygon',
                'string',
                $value->polygon
            );
        }
    }

    public function getEmptyValue()
    {
        return new Value;
    }

    public function validate( FieldDefinition $fieldDefinition, SPIValue $fieldValue )
    {
        $errors = [];

        if ( $this->isEmptyValue( $fieldValue ) ) {
            return $errors;
        }

        // Polygon validation
        if ( !is_string( $fieldValue->polygon ) ) {
            $errors[] = new ValidationError(
                'Polygon is not string: %polygon%',
                null,
                [ '%polygon%' => $fieldValue->polygon ]
            );
        }

        return $errors;
    }

    public function getFieldName( SPIValue $value )
    {
        return (string)$value->polygon;
    }

    /**
     * Returns information for FieldValue->$sortKey relevant to the field type.
     *
     * @param \Donfelice\PolygonFieldTypeBundle\eZ\Publish\Polygon\Value $value
     *
     * @return bool
     */
    protected function getSortInfo( BaseValue $value )
    {
        //return (string)$value->polygon;
        return false;
    }

    /***
     * In the eZ\Publish\SPI\FieldType\FieldType interface there is also getName() method that is currently deprecated and replaced by getFieldName().
     * You can throw an exception in its body to make sure it isn't called anywhere
     */
    public function getName( SPIValue $value)
    {
        throw new \RuntimeException(
            'Name generation provided via NameableField set via "ezpublish.fieldType.nameable" service tag'
        );
    }

    public function fromHash( $hash )
    {
        if ( $hash === null ) {
            return $this->getEmptyValue();
        }

        return new Value( $hash );
    }

    public function toHash( SPIValue $value )
    {
        if ( $this->isEmptyValue( $value ) ) {
            return null;
        }

        return [
            'polygon' => $value->polygon
        ];
    }

    public function toPersistenceValue( SPIValue $value )
    {
        if ( $value === null ) {
            return new PersistenceValue(
                [
                    'data' => null,
                    'externalData' => null,
                    'sortKey' => null,
                ]
            );
        }

        return new PersistenceValue(
            [
                'data' => $this->toHash( $value ),
                'sortKey' => $this->getSortInfo( $value ),
            ]
        );
    }

    public function fromPersistenceValue( PersistenceValue $fieldValue )
    {
        if ( $fieldValue->data === null ) {
            return $this->getEmptyValue();
        }

        return new Value( $fieldValue->data );
    }

    /**
     * Validates the fieldSettings of a FieldDefinitionCreateStruct or FieldDefinitionUpdateStruct.
     *
     * @param mixed $fieldSettings
     *
     * @return \eZ\Publish\SPI\FieldType\ValidationError[]
     */
    public function validateFieldSettings( $fieldSettings )
    {
        $validationErrors = array();
        if ( !is_array( $fieldSettings ) ) {
            $validationErrors[] = new ValidationError( 'Field settings must be in form of an array' );
            return $validationErrors;
        }
        foreach ( $fieldSettings as $name => $value ) {
            if ( !isset( $this->settingsSchema[$name] ) ) {
                $validationErrors[] = new ValidationError(
                    "'%setting%' setting is unknown",
                    null,
                    array(
                        '%setting%' => $name,
                    )
                );
                continue;
            }
            switch ( $name ) {
                case 'geojson':
                    if (!is_bool($value)) {
                        $validationErrors[] = new ValidationError(
                            "'%setting%' setting value must be of boolean type",
                            null,
                            array(
                                '%setting%' => $name,
                            )
                        );
                    }
                    break;
            }
        }
        return $validationErrors;
    }

    /**
     * Returns if the given $value is considered empty by the field type.
     *
     * Default implementation, which performs a "==" check with the value
     * returned by {@link getEmptyValue()}. Overwrite in the specific field
     * type, if necessary.
     *
     * @param \eZ\Publish\SPI\FieldType\Value|\Donfelice\Bundle\PolygonBundle\Core\FieldType\Polygon\Value $value
     *
     * @return bool
     */
    public function isEmptyValue( SPIValue $value )
    {
        //return $value === null;
        return $value->polygon === null || trim( $value->polygon ) === '';
    }

    /**
     * Indicates if the field type supports indexing and sort keys for searching.
     *
     * @return bool
     */
    public function isSearchable()
    {
        return false;
    }

}
