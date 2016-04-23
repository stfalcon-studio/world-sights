<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Sight;
use AppBundle\EventListener\SightConvertListener;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SightType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('phone')
            ->add('website')
            ->add('latitude')
            ->add('longitude')
            ->add('sight_type', EntityType::class, [
                'class'         => 'AppBundle\Entity\SightType',
            ])
            ->add('locality', EntityType::class, [
                'class' => 'AppBundle\Entity\Locality',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => 'AppBundle\Entity\Sight',
            'csrf_protection' => false,
        ]);
    }
}
