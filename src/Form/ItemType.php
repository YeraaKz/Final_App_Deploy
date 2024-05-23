<?php

namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
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
                $builder->add($attribute->getName(), TextType::class, [
                    'label' => $attribute->getName(),
                    'attr' => ['class' => 'form-control mb-3'],
                    'mapped' => false,
                    'required' => false
                ]);
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
}
