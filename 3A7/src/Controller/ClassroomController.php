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
     * @Route("/listclassroom", name="listclassroom")
     */
    public function list(ClassroomRepository $c):Response
    {
        //$classrooms = $this->getDoctrine()->getRepository(Classroom::class)->findAll();
        $classrooms = $c->findAll();
        return $this->render('classroom/list.html.twig',['list'=>$classrooms]);
    }

    /**
     * @Route("/details/{id}", name="detail")
     */
    public function details($id):Response
    {
        $classroom= $this->getDoctrine()->getRepository(Classroom::class)->find($id);
        return $this->render('classroom/detail.html.twig',['class'=>$classroom]);
    }

    /**
     * @Route("/addClassroom", name="addClassroom")
     */
    public function add(Request $req)
    {
        $class = new Classroom();
        $form = $this->createForm(ClassroomType::class,$class);
        $form->handleRequest($req);
        if($form->isSubmitted()){
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
    public function delete($id,EntityManagerInterface $manager)
    {
        $manager->remove($this->getDoctrine()->getRepository(Classroom::class)->find($id));
        $manager->flush();
        return $this->redirectToRoute('listclassroom');
    }

    /**
     * @Route("/updateClassroom/{id}", name="updateClassroom")
     */
    public function update(Request $req,$id,ClassroomRepository $rep)
    {/* $this->getDoctrine()->getRepository(Classroom::class)*/
        $class = $rep->find($id);
        $form = $this->createForm(ClassroomType::class,$class);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listclassroom');
        }
        return $this->render('classroom/add.html.twig',['formClass'=>$form->createView()]);
    }


}
