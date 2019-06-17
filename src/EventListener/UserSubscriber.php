<?php
/**
 * Created by PhpStorm.
 * User: Eric
 */

namespace App\EventListener;

use App\Entity\User;
use Monolog\Logger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
{

    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * ['eventName' => 'methodName']
     *  * ['eventName' => ['methodName', $priority]]
     *  * ['eventName' => [['methodName1', $priority], ['methodName2']]]
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            UserSubscriber::class => 'updatingUser'
        ];
    }

    public function updatingUser(User $user)
    {

        $message = "User ".$user->getFirstname()." ".$user->getLastname()." has been updated";

        $this->logger->notice($message); //évenement intéressant

    }
}