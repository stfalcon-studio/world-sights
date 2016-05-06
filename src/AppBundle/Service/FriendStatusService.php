<?php

namespace AppBundle\Service;

use AppBundle\DBAL\Types\FriendStatusType;
use AppBundle\Entity\Friend;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;

/**
 * FriendStatusService
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class FriendStatusService
{
    /** @var  EntityManager $em Entity Manager */
    private $em;

    /**
     * Constructor
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function updateFriendStatus(Friend $friend, Form $form)
    {
        $view = new View();

        $status   = $friend->getStatus();
        $statusBD = $this->em->getRepository('AppBundle:User')
                             ->findFriendStatusByUserAndFriend($friend->getUser(), $friend->getFriend())['status'];

        switch ($statusBD) {
            case FriendStatusType::SENT:
                switch ($status) {
                    case FriendStatusType::SENT:
                        $view = $this->generateView($friend);
                        break;
                    case FriendStatusType::RECEIVED:
                        $view = $this->generateErrorView($form, 'Status sent cannot change the status of the received');
                        break;
                    case FriendStatusType::REJECTED:
                        $this->updateFriend($friend);
                        $view = $this->generateView($friend);
                        break;
                    case FriendStatusType::ACCEPTED:
                        $view = $this->generateErrorView($form, 'Status sent cannot change the status of the accepted');
                        break;
                }
                break;
            case FriendStatusType::RECEIVED:
                switch ($status) {
                    case FriendStatusType::SENT:
                        $view = $this->generateErrorView($form, 'Status received cannot change to the status of the sent');
                        break;
                    case FriendStatusType::REJECTED:
                        $view = $this->generateView($friend);
                        break;
                    case FriendStatusType::RECEIVED:
                    case FriendStatusType::ACCEPTED:
                        $this->updateFriend($friend);
                        $view = $this->generateView($friend);
                        break;
                }
                break;
            case FriendStatusType::REJECTED:
                switch ($status) {
                    case FriendStatusType::SENT:
                        $view = $this->generateErrorView($form, 'Status rejected cannot change to the status of the sent');
                        break;
                    case FriendStatusType::RECEIVED:
                        $view = $this->generateErrorView($form, 'Status rejected cannot change to the status of the received');
                        break;
                    case FriendStatusType::REJECTED:
                        $view = $this->generateView($friend);
                        break;
                    case FriendStatusType::ACCEPTED:
                        $this->updateFriend($friend);
                        $view = $this->generateView($friend);
                        break;
                }
                break;
            case FriendStatusType::ACCEPTED:
                switch ($status) {
                    case FriendStatusType::SENT:
                        $view = $this->generateErrorView($form, 'Status accepted cannot change to the status of the sent');
                        break;
                    case FriendStatusType::RECEIVED:
                        $view = $this->generateErrorView($form, 'Status accepted cannot change to the status of the received');
                        break;
                    case FriendStatusType::REJECTED:
                        $this->updateFriend($friend);
                        $view = $this->generateView($friend);
                        break;
                    case FriendStatusType::ACCEPTED:
                        $view = $this->generateView($friend);
                        break;
                }
        }

        return $view;
    }

    /**
     * Generate view
     *
     * @param Friend $friend Friend
     *
     * @return View
     */
    private function generateView(Friend $friend)
    {
        $view = View::create([
            'code'   => Response::HTTP_OK,
            'friend' => $friend,
        ]);
        $view->setSerializationContext(SerializationContext::create()->setGroups(['friend']));

        return $view;
    }

    /**
     * Generate error view
     *
     * @param Form   $form    Form
     * @param string $message Message
     *
     * @return View
     */
    private function generateErrorView(Form $form, $message)
    {
        $form->get('status')->addError(new FormError($message));

        $view = View::create($form, Response::HTTP_BAD_REQUEST);

        return $view;
    }

    /**
     * Update friend in BD
     *
     * @param Friend $friend Friend
     */
    private function updateFriend(Friend $friend)
    {
        $this->em->persist($friend);
        $this->em->flush();
    }
}
