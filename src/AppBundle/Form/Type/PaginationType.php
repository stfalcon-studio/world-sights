<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Pagination Type
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class PaginationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('limit', IntegerType::class, [
                'empty_data'  => 10,
                'required'    => false,
                'description' => 'Limit',
            ])
            ->add('offset', IntegerType::class, [
                'empty_data'  => 0,
                'required'    => false,
                'description' => 'Offset',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => 'AppBundle\Form\Model\Pagination',
            'csrf_protection' => false,
            'method'          => 'GET',
        ]);
    }
}
