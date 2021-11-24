<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user", methods={"POST"} )
     */
    public function registration(Request $request, UserRepository $userRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data["login"])) {
            return $this->json([
                'status' => 400,
                'text' => "file doesn't contain login"
            ]);
        }

        if (!isset($data["password"])) {
            return $this->json([
                'status' => 400,
                'text' => "file doesn't contain password"
            ]);
        }

        if ($data["login"] == null) {
            return $this->json([
                'status' => 400,
                'text' => "login not entered"
            ]);
        }
        if ($data["password"] == null) {
            return $this->json([
                'status' => 400,
                'text' => "password not entered"
            ]);
        }

        if ($userRepository->findOneBy(["login"=>$data["login"]]) != null)
        {
            return $this->json([
                'status' => 400,
                'text' => "user with this login alredy exist"
            ]);
        }

        $user = new User();
        $user->setLogin($data["login"]);
        $user->setPassword($data["password"]);
        $db = $this->getDoctrine()->getManager();

        $db->persist($user);
        $db->flush();

        return $this->json([
            'status' => 200,
            'text' => "registration completed"
        ]);
    }
}
