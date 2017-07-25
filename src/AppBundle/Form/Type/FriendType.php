<?php

namespace AppBundle\Form\Type;

use AppBundle\DBAL\Types\FriendStatusType;
use AppBundle\Event\AddUserToFriendEvent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Friend Type
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
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
            ])
            ->add('status', ChoiceType::class, [
                'choices'    => FriendStatusType::getChoices(),
                'empty_data' => FriendStatusType::SENT,
            ]);

        $token = $this->tokenStorage;

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($token) {
            $friend = $event->getData();
            $this->eventDispatcher->dispatch('event.add_user_to_friend', new AddUserToFriendEvent($token, $friend));
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
