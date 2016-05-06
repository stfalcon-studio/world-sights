<?php

namespace AppBundle\Form\Type;

use AppBundle\Event\AddUserEvent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class FriendType extends AbstractType
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
            ->add('user', EntityType::class, [
                'class' => 'AppBundle\Entity\User',
            ])
            ->add('friend', EntityType::class, [
                'class' => 'AppBundle\Entity\User',
            ]);

        $token = $this->tokenStorage;

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($token) {
            $friend = $event->getData();
            $this->eventDispatcher->dispatch(FormEvents::SUBMIT , new AddUserEvent($token, $friend));
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => 'AppBundle\Entity\Friend',
            'csrf_protection' => false,
        ]);
    }
}
