<?php

namespace App\Form;

use App\Entity\Item;
use App\Enum\CustomItemAttributeDatatype;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control']
            ])
            ->add('tags', TextType::class, [
                'label' => 'Tags (separated by spaces)',
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'form-control',
                    'autocomplete' => 'off',
                    'id' => 'tag-input'],
            ])
        ;
        if (!empty($options['custom_attributes'])) {
            foreach ($options['custom_attributes'] as $attribute) {
                $builder->add(
                    str_replace(' ', '_', $attribute->getName()),
                    $this->getFieldType($attribute->getType()->value),
                    [
                    'label' => $attribute->getName(),
                        'attr' => ['class' => $attribute->getType()->value === CustomItemAttributeDatatype::Boolean->value ? 'form-check-input' : 'form-control mb-3'],
                    'mapped' => false,
                    'required' => false
                    ]
                );
            }
        }

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
            'custom_attributes' => []
        ]);
    }

    private function getFieldType(string $type)
    {
        return match ($type) {
            CustomItemAttributeDatatype::Date->value => DateType::class,
            CustomItemAttributeDatatype::Integer->value => NumberType::class,
            CustomItemAttributeDatatype::Text->value => TextareaType::class,
            CustomItemAttributeDatatype::Boolean->value => CheckboxType::class,
            default => TextType::class,
        };
    }
}
