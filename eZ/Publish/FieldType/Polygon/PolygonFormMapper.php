<?php

namespace Donfelice\PolygonFieldTypeBundle\eZ\Publish\FieldType\Polygon;

//use eZ\Publish\API\Repository\FieldTypeService;
use EzSystems\RepositoryForms\Data\Content\FieldData;
use EzSystems\RepositoryForms\Data\FieldDefinitionData;
use EzSystems\RepositoryForms\FieldType\DataTransformer\FieldValueTransformer;
use EzSystems\RepositoryForms\FieldType\FieldDefinitionFormMapperInterface;
use EzSystems\RepositoryForms\FieldType\FieldValueFormMapperInterface;
use Donfelice\PolygonFieldTypeBundle\Form\Type\FieldType\PolygonFieldType;
//use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
//use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

use eZ\Publish\SPI\FieldType\Value as BaseValue;

/**
 * FormMapper for ezpolygon FieldType.
 */
class PolygonFormMapper implements FieldDefinitionFormMapperInterface, FieldValueFormMapperInterface
{

    /***
     *
     * Implementing EzSystems\RepositoryForms\FieldType\FieldDefinitionFormMapperInterface
     * to provide Field Type definition editing support
     *
     */
    public function mapFieldDefinitionForm( FormInterface $fieldDefinitionForm, FieldDefinitionData $data )
    {
        $fieldDefinitionForm
            ->add(
                  'geojson',
                  CheckboxType::class, [
                      'required' => false,
                      'property_path' => 'fieldSettings[geojson]',
                      'label' => 'field_definition.ezpolygon.geojson',
                  ]
            );
      }


      /***
       *
       * Implementing EzSystems\RepositoryForms\FieldType\Mapper\FieldValueFormMapperInterface
       * to provide editing support
       *
       */
      public function mapFieldValueForm( FormInterface $fieldForm, FieldData $data )
      {
          $fieldDefinition = $data->fieldDefinition;
          $formConfig = $fieldForm->getConfig();

          //var_dump($fieldDefinition);

          $fieldForm
              ->add(
                  $formConfig->getFormFactory()->createBuilder()
                      ->create(
                          'value',
                          PolygonFieldType::class,
                          array(
                              'required' => $data->fieldDefinition->isRequired,
                              'label' => $data->fieldDefinition->getName(),
                              'field_definition' => $data->fieldDefinition,
                          )
                      )
                      //->addModelTransformer( new FieldValueTransformer( new Value( $data) ) )
                      ->setAutoInitialize( false )
                      ->getForm()
              );
      }

}
