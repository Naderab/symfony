<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Entity\Student;
use App\Form\ClassroomType;
use App\Form\StudentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index():Response{
        return new Response("bonjour mes étudiants") ;
    }

    /**
     * @Route("/addStudent", name="addStudent")
     */
    public function addStudent(Request $req)
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class,$student);
        $form->handleRequest($req);
        if($form->isSubmitted())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($student);
            $em->flush();
            //return $this->redirectToRoute('listclassroom');
        }

        return $this->render('student/add.html.twig',['formStudent'=>$form->createView()]);
    }
}