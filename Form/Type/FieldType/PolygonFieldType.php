<?php

namespace Donfelice\PolygonFieldTypeBundle\Form\Type\FieldType;

use eZ\Publish\API\Repository\FieldTypeService;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use EzSystems\RepositoryForms\FieldType\DataTransformer\FieldValueTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form Type representing ezpolygon field type.
 */
class PolygonFieldType extends AbstractType
{


    protected $fieldTypeService;

    public function __construct( FieldTypeService $fieldTypeService )
    {
        $this->fieldTypeService = $fieldTypeService;
    }


    public function configureOptions( OptionsResolver $resolver )
    {
        $resolver->setRequired( array( 'field_definition' ) );
        $resolver->setAllowedTypes( 'field_definition', FieldDefinition::class );
    }

    public function buildForm( FormBuilderInterface $builder, array $options )
    {

        /** @var \eZ\Publish\API\Repository\Values\ContentType\FieldDefinition $fieldDefinition */
        $fieldDefinition = $options['field_definition'];

        $builder
            ->add(
                'polygon',
                TextType::class,
                    [
                        'required' => $fieldDefinition->isRequired,
                        //'label' => $fieldDefinition->getName(
                        //$formConfig->getOption('languageCode')
                        //),
                        'label' => 'GeoJSON object',
                    ]
            )
            ->addModelTransformer(
                new FieldValueTransformer(
                    $this->fieldTypeService->getFieldType( 'ezpolygon' ),
                    $fieldDefinition
                )
            );

    }

    public function getBlockPrefix()
    {
        return 'ezplatform_fieldtype_ezpolygon';
    }

}
