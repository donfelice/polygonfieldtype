<?php

namespace Donfelice\PolygonFieldTypeBundle\eZ\Publish\FieldType\Polygon;

use Symfony\Component\Form\DataTransformerInterface;

class ValueTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if (!$value instanceof Value) {
            return null;
        }
        return $value->text;
    }
    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }
        return new Value($value);
    }
}
