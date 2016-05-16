<?php

namespace AppBundle\Form\Type;

use AppBundle\Event\AddUserToSightPhotoEvent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Sight Photo Type
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class SightPhotoType extends AbstractType
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
            ->add('photo_file', FileType::class)
            ->add('name')
            ->add('description')
            ->add('sight', EntityType::class, [
                'class' => 'AppBundle\Entity\Sight',
            ])
            ->add('user', EntityType::class, [
                'class' => 'AppBundle\Entity\User',
            ]);

        $token = $this->tokenStorage;

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($token) {
            $sightPhoto = $event->getData();
            $this->eventDispatcher->dispatch('event.add_user_to_sight_photo', new AddUserToSightPhotoEvent($token, $sightPhoto));
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => 'AppBundle\Entity\SightPhoto',
            'csrf_protection' => false,
        ]);
    }
}
