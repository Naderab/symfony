<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Form\ClassroomType;
use App\Repository\ClassroomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClassroomController extends AbstractController
{
    /**
     * @Route("/classroom", name="classroom")
     */
    public function index(): Response
    {
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }

    /**
     * @Route("/listClassroom", name="listclassroom")
     */
    public function list(ClassroomRepository $c):Response
    {
        //$classrooms = $this->getDoctrine()->getRepository(Classroom::class)->findAll();
        $classrooms = $c->findAll();
        return $this->render('classroom/list.html.twig',['list'=>$classrooms]);
    }
    /**
     * @Route("/detailClass/{id}", name="detail")
     */
    public function detail($id):Response
    {
        $classroom = $this->getDoctrine()->getRepository(Classroom::class)->find($id);
        return $this->render('classroom/detail.html.twig',['classroom'=>$classroom]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request):Response
    {
        $classroom= new Classroom();
        $form=$this->createForm(ClassroomType::class,$classroom);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($classroom);
            $em->flush();
            return $this->redirectToRoute('listclassroom');
        }
        return $this->render('classroom/add.html.twig',['formClass'=>$form->createView()]);
    }
}
