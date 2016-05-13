<?php

namespace AppBundle\Form\Type;

use AppBundle\Event\AddUserToSightVisitEvent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Sight Visit Type
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class SightVisitType extends AbstractType
{
    /**
     * @var TokenStorageInterface $tokenStorage Token storage
     */
    private $tokenStorage;

    /**
     * @var EventDispatcherInterface $eventDispatcher Event dispatcher
     */
    private $eventDispatcher;

    /**
     * Constructor
     *
     * @param TokenStorageInterface    $tokenStorage    Token storage
     * @param EventDispatcherInterface $eventDispatcher Event dispatcher
     */
    public function __construct(TokenStorageInterface $tokenStorage, EventDispatcherInterface $eventDispatcher)
    {
        $this->tokenStorage    = $tokenStorage;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sight', EntityType::class, [
                'class' => 'AppBundle\Entity\Sight',
            ])
            ->add('user', EntityType::class, [
                'class' => 'AppBundle\Entity\User',
            ])
            ->add('date', DateTimeType::class, [
                'widget'      => 'single_text',
                'date_format' => 'yyyy-MM-dd H:mm',
            ]);

        $token = $this->tokenStorage;

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($token) {
            $sightVisit = $event->getData();
            $this->eventDispatcher->dispatch('event.add_user_to_sight_visit', new AddUserToSightVisitEvent($token, $sightVisit));
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => 'AppBundle\Entity\SightVisit',
            'csrf_protection' => false,
        ]);
    }
}
