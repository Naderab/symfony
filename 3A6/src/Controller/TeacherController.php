<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeacherController extends AbstractController
{
    /**
     * @Route("/teacher", name="teacher")
     */
    public function index(): Response
    {
        return $this->render('teacher/index.html.twig', [
            'controller_name' => 'TeacherController',
        ]);
    }

    /**
     * @Route("/showTeacher/{name}", name="showTeacher")
     */
    public function showTeacher($name):Response
    {
        return new Response("Bonjour ".$name);
    }

    /**
     * @Route("/showTeacherTwig/{name}", name="showTeacher1")
     */
    public function showTeacher1($name):Response
    {
        return $this->render("teacher/showTeacher.html.twig",["name"=>$name]);
    }
}
