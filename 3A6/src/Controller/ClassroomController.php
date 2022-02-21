<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Form\ClassroomType;
use App\Repository\ClassroomRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("/addClass", name="addClass")
     */
    public function add(Request $req)
    {
        $class = new Classroom();
        $form = $this->createForm(ClassroomType::class,$class);
        $form->handleRequest($req);
        if($form->isSubmitted())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($class);
            $em->flush();
            return $this->redirectToRoute('listclassroom');
        }

        return $this->render('classroom/add.html.twig',['formClass'=>$form->createView()]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete($id,EntityManagerInterface $em,ClassroomRepository $rep)
    {
        /*$class = $this->getDoctrine()->getRepository(Classroom::class)->find($id);
        $em1 = $this->getDoctrine()->getManager();
        $em1->remove($class);
        $em1->flush();*/

        $em->remove($rep->find($id));
        $em->flush();
        return $this->redirectToRoute('listclassroom');
    }
    /**
     * @Route("/update/{id}", name="update")
     */
    public function update(Request $req,$id)
    {
        $class = $this->getDoctrine()->getRepository(Classroom::class)->find($id);
        $form = $this->createForm(ClassroomType::class,$class);
        $form->handleRequest($req);
        if($form->isSubmitted())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listclassroom');
        }

        return $this->render('classroom/add.html.twig',['formClass'=>$form->createView()]);
    }
}
