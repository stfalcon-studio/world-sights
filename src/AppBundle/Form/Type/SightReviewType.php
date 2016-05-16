<?php

namespace AppBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Sight Review Type
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class SightReviewType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('topic', TextType::class)
            ->add('description', TextareaType::class)
            ->add('mark', IntegerType::class)
            ->add('sight', EntityType::class, [
                'class' => 'AppBundle\Entity\Sight',
            ])
            ->add('user', EntityType::class, [
                'class' => 'AppBundle\Entity\User',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => 'AppBundle\Entity\SightReview',
            'csrf_protection' => false,
        ]);
    }
}
