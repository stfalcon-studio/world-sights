<?php

namespace AppBundle\Form\Type;

use AppBundle\DBAL\Types\SightTicketType as SightTicketTypeDBAL;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SightTicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('link_buy', TextType::class)
            ->add('type', ChoiceType::class, [
                'choices' => SightTicketTypeDBAL::getChoices(),
            ])
            ->add('sight', EntityType::class, [
                'class' => 'AppBundle\Entity\Sight',
            ])
            ->add('from', EntityType::class, [
                'class' => 'AppBundle\Entity\Locality',
            ])
            ->add('to', EntityType::class, [
                'class' => 'AppBundle\Entity\Locality',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => 'AppBundle\Entity\SightTicket',
            'csrf_protection' => false,
        ]);
    }
}
