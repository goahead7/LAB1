<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ToDo;
use App\Repository\ToDoRepository;
use App\Repository\UserRepository;

class ToDoController extends AbstractController
{
    /**
     * @Route("/todo", name="post_todo", methods={"POST"} )
     */

    public function create_todo(Request $request, UserRepository $userRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data["login"])) {
            return $this->json(
                [
                'status' => 400,
                'text' => "file doesn't contain login"
                ]
            );
        }

        if (!isset($data["password"])) {
            return $this->json(
                [
                'status' => 400,
                'text' => "file doesn't contain password"
                ]
            );
        }

        if (!isset($data["discription"])) {
            return $this->json(
                [
                'status' => 400,
                'text' => "file doesn't contain discription"
                ]
            );
        }

        if (!isset($data["todo_name"])) {
            return $this->json(
                [
                'status' => 400,
                'text' => "file doesn't contain todo_name"
                ]
            );
        }

        if ($data["login"] == null) {
            return $this->json(
                [
                'status' => 400,
                'text' => "login not entered"
                ]
            );
        }
        if ($data["password"] == null) {
            return $this->json(
                [
                'status' => 400,
                'text' => "password not entered"
                ]
            );
        }

        if ($data["todo_name"] == null) {
            return $this->json(
                [
                'status' => 400,
                'text' => "todo_name not entered"
                ]
            );
        }

        $user = $userRepository->findOneBy(["login"=>$data["login"]]);
        if ($user == null) {
            return $this->json(
                [
                'status' => 400,
                'text'=> "this user is not registered"
                ]
            );
        }
        if ($user->getPassword() != $data["password"]) {
            return $this->json(
                [
                'status' => 400,
                'text'=> "password error"
                ]
            );
        }

        $todo = new ToDo();
        $todo->setUser($user);
        $todo->setTodoName($data["todo_name"]);
        $todo->setDiscription($data["discription"]);
        $user->addTodoList($todo);
        $db = $this->getDoctrine()->getManager();

        $db->persist($todo);
        $db->persist($user);
        $db->flush();
        return $this->json(
            [
            'status' => 200,
            'text'=> "todo was added"
            ]
        );
    }

    /**
     * @Route("/todo", name="get_todo", methods={"GET"} )
     */

    public function get_todo(Request $request, ToDoRepository $toDoRepository, UserRepository $userRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data["login"])) {
            return $this->json(
                [
                'status' => 400,
                'text' => "file doesn't contain login"
                ]
            );
        }

        if (!isset($data["password"])) {
            return $this->json(
                [
                'status' => 400,
                'text' => "file doesn't contain password"
                ]
            );
        }

        if ($data["login"] == null) {
            return $this->json(
                [
                'status' => 400,
                'text' => "login not entered"
                ]
            );
        }
        if ($data["password"] == null) {
            return $this->json(
                [
                'status' => 400,
                'text' => "password not entered"
                ]
            );
        }
        $user = $userRepository->findOneBy(["login"=>$data["login"]]);

        if ($user == null) {
            return $this->json(
                [
                'status' => 400,
                'text'=> "error user"
                ]
            );
        }
        if ($user->getPassword() != $data["password"]) {
            return $this->json(
                [
                'status' => 400,
                'text'=> "password error"
                ]
            );
        }

        $todolist = $toDoRepository->findBy(["user"=>$user]);

        if ($todolist == null) {
            return $this->json(
                [
                'status' => 400,
                'text' => "todo is null"]
            );
        }

        foreach ($todolist as $todo) {
            $todolist_arr = [
                "name" => $todo->getTodoName(),
                "discription" => $todo->getDiscription()];
            $result[] = $todolist_arr;
        }

        return $this->json(
            [
            'status' => 200,
            'text'=> $result
            ]
        );
    }

    /**
     * @Route("/todo/{id}", name="put_todo", methods={"PUT"} )
     */

    public function put_todo(Request $request, ToDoRepository $toDoRepository, UserRepository $userRepository, $id): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data["login"])) {
            return $this->json(
                [
                'status' => 400,
                'text' => "file doesn't contain login"
                ]
            );
        }

        if (!isset($data["password"])) {
            return $this->json(
                [
                'status' => 400,
                'text' => "file doesn't contain password"
                ]
            );
        }

        if (!isset($data["discription"])) {
            return $this->json(
                [
                'status' => 400,
                'text' => "file doesn't contain discription"
                ]
            );
        }

        if (!isset($data["todo_name"])) {
            return $this->json(
                [
                'status' => 400,
                'text' => "file doesn't contain todo_name"
                ]
            );
        }

        if ($data["login"] == null) {
            return $this->json(
                [
                'status' => 400,
                'text' => "login not entered"
                ]
            );
        }
        if ($data["password"] == null) {
            return $this->json(
                [
                'status' => 400,
                'text' => "password not entered"
                ]
            );
        }

        if ($data["todo_name"] == null) {
            return $this->json(
                [
                'status' => 400,
                'text' => "todo_name not entered"
                ]
            );
        }
        $user = $userRepository->findOneBy(["login" => $data["login"]]);

        if ($user == null) {
            return $this->json(
                [
                'status' => 400,
                'text' => "error user"
                ]
            );
        }
        if ($user->getPassword() != $data["password"]) {
            return $this->json(
                [
                'status' => 400,
                'text' => "password error"
                ]
            );
        }

        $todo = $toDoRepository->find($id);

        if ($todo == null) {
            return $this->json(
                [
                'status' => 400,
                'text' => "no this todo"
                ]
            );
        }

        if ($user != $todo->getUser()) {
            return $this->json(
                [
                'status' => 400,
                'text' => "can't change this todo"
                ]
            );
        }
        $todo->setTodoName($data["todo_name"]);
        $todo->setDiscription($data["discription"]);

        $db = $this->getDoctrine()->getManager();

        $db->merge($todo);
        $db->flush();

        return $this->json(
            [
            'status' => 200,
            'text' => "todo change"
            ]
        );
    }
    /**
     * @Route("/todo/{id}", name="delete_todo", methods={"DELETE"} )
     */
    public function delete_todo(Request $request, ToDoRepository $toDoRepository, UserRepository $userRepository, $id): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data["login"])) {
            return $this->json(
                [
                'status' => 400,
                'text' => "file doesn't contain login"
                ]
            );
        }

        if (!isset($data["password"])) {
            return $this->json(
                [
                'status' => 400,
                'text' => "file doesn't contain password"
                ]
            );
        }

        if ($data["login"] == null) {
            return $this->json(
                [
                'status' => 400,
                'text' => "login not entered"
                ]
            );
        }
        if ($data["password"] == null) {
            return $this->json(
                [
                'status' => 400,
                'text' => "password not entered"
                ]
            );
        }

        $user = $userRepository->findOneBy(["login" => $data["login"]]);

        if ($user == null) {
            return $this->json(
                [
                'status' => 400,
                'text' => "error user"
                ]
            );
        }
        if ($user->getPassword() != $data["password"]) {
            return $this->json(
                [
                'status' => 400,
                'text' => "password error"
                ]
            );
        }

        $todo = $toDoRepository->find($id);


        if ($todo == null) {
            return $this->json(
                [
                'status' => 400,
                'text' => "todo not remove"
                ]
            );
        }

        if ($user != $todo->getUser()) {
            return $this->json(
                [
                'status' => 400,
                'text' => "todo not remove"
                ]
            );
        }
        $user->removeTodoList($todo);
        $db = $this->getDoctrine()->getManager();

        $db->merge($user);
        $db->remove($todo);
        $db->flush();

        return $this->json(
            [
            'status' => 200,
            'text' => "todo remove"
            ]
        );
    }
}
