<?php

namespace App\Controller;

use App\EventListener\UserSubscriber;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\User;

/**
 * Created by PhpStorm.
 * User: Eric
 */

class UserController extends AbstractController
{

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/", name="showUser")
     */
    public function showUser(EntityManagerInterface $em)
    {

        $userslist = $em->getRepository(User::class)->findAll();

        if ($userslist == null) {
            return new JsonResponse("No user in the database", 400);
        }

        foreach ($userslist as $userRow) {
            $firstName = $userRow->getFirstname();
            $lastName = $userRow->getLastname();
            $creationDate = $userRow->getCreationdate();
            $updatedDate = $userRow->getUpdatedate();

            $usersList[] = ['id' => $userRow->getId(), 'firstName' => $firstName, 'lastName' => $lastName, 'creationDate' => $creationDate, 'updatedDate' => $updatedDate];

        }

        return new JsonResponse(['users' => $usersList], 200);

    }

    /**
     * @Route("/createUserDb", name="createUserDb")
     */
    public function createUserDb(Request $request, EntityManagerInterface $em, ValidatorInterface $validator) : Response
    {

        $user = new User();

        $content = json_decode($request->getContent());
        $user->setFirstname($content->firstname);
        $user->setLastname($content->lastname);
        $user->setCreationdate($content->creationdata);
        $user->setUpdatedate($content->updatedate);

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return new JsonResponse((string)$errors, 400);
        }

        $em->persist($user);
        $em->flush();
        return new JsonResponse('user created', 200);

    }

    /**
     * @Route("/getUserDb", name="getUserDb")
     */
    public function getUserDb(Request $request, EntityManagerInterface $em)
    {

        $user = $em->getRepository(User::class)->findOneBy(['id' => json_decode($request->getContent())->id]);
        if (isset($user)) {
            return new JsonResponse(['id' => $user->getId(), 'firstName' => $user->getFirstName(), 'lastName' => $user->getLastName(), 'creationDate' => $user->getCreationDate(), 'updatedDate' => $user->getUpdatedate()], 200);
        }
        return new JsonResponse("User not found", 400);

    }

    /**
     * @Route("/deleteUserDb", name="deleteUserDb")
     */
    public function deleteUserDb(Request $request, EntityManagerInterface $em)
    {

        $user = $em->getRepository(User::class)->findOneBy(['id' => json_decode($request->getContent())->id]);
        if (isset($user)) {
            $em->remove($user);
            $em->flush();

            return new JsonResponse("user: " . $user->getFirstName() . " " . $user->getLastName() . " deleted", 200);
        }
        return new JsonResponse("User not found", 400);

    }

    /**
     * @Route("/updateUserDb", name="updateUserDb")
     */
    public function updateUserDb(Request $request, EntityManagerInterface $em)
    {

        $user = $em->getRepository(User::class)->findOneBy(['id' => json_decode($request->getContent())->id]);

        if (isset($user)) {

            if (isset(json_decode($request->getContent())->firstname)) { $user->setFirstname(json_decode($request->getContent())->firstname); }
            if (isset(json_decode($request->getContent())->lastname)) { $user->setLastname(json_decode($request->getContent())->lastname); }

            $user->setUpdatedate(new \DateTime());
            $em->persist($user);
            $em->flush();

            /*$userChanged = new UserChanged($this->logger);
            $userChanged->updatingUserData($user);*/

            $userChanged = new UserSubscriber($this->logger);
            $userChanged->updatingUser($user);

            return new JsonResponse("User ".$user->getFirstname()." ".$user->getLastname()." has been updated", 200);

        }

        return new JsonResponse("User not found", 400);

    }

}