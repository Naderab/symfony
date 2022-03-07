<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Entity\Student;
use App\Form\ClassroomType;
use App\Form\SearchAVGType;
use App\Form\SearchNSCType;
use App\Form\SearchType;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
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
            return $this->redirectToRoute('listStudent');
        }
        return $this->render('student/add.html.twig',['formStudent'=>$form->createView()]);
    }

    /**
     * @Route ("/listStudent",name="listStudent")
     */
    public function listStudent(StudentRepository $s,Request $req):Response{
    $student=$s->findAll();
    $studentByEmail=$s->listStudentOrdredByEmail();
    $form = $this->createForm(SearchNSCType::class);
    $form->handleRequest($req);
    if($form->isSubmitted())
    {
        $nsc = $form['nsc']->getData();
        //dd($nsc);
        $studentSearch = $s->searchByNsc($nsc);
        return $this->render('student/listStudent.html.twig', ['student'=>$studentSearch,'byEmail'=>$studentByEmail,'form'=>$form->createView()]);

    }
    return $this->render('student/listStudent.html.twig', ['student'=>$student,'byEmail'=>$studentByEmail,'form'=>$form->createView()]);
    }
    /**
     * @Route ("/deletestudent/{nsc}",name="deletestudent")
     */
    public function remove($nsc,EntityManagerInterface $em,StudentRepository $s):Response{

        $em->remove($s->find($nsc));
        $em->flush();
        return $this->redirectToRoute('listStudent');
    }

    /**
     * @Route ("/updatestudent/{nsc}",name="updatestudent")
     */
    public function update(Request $R,$nsc ):Response{
        $student=$this->getDoctrine()->getRepository(Student::class)->find($nsc);
        $form=$this->createForm(StudentType::class,$student);
        $form->handleRequest($R);
        if($form->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listStudent');
        }
        return $this->render('student/add.html.twig',['formStudent'=>$form->createView()]);

    }

    /**
     * @Route ("/student/classroom/{id}",name="studentsByClassroom")
     */
    public function studentsByClassroom($id,StudentRepository $repository){
        $students=$repository->listStudentByClassroom($id);

        return $this->render('classroom/listStudentByClassroom.html.twig',['students'=>$students]);
    }
    /**
     * @Route("/students/avg",name="studentsAVG")
     */
    public function studentsAvg(Request $req,StudentRepository $repository)
    {
        $students = $repository->findAll();
        $studentNotAddmited = $repository->studentsNotAddmited();
        $form = $this->createForm(SearchAVGType::class);
        $form->handleRequest($req);
        if($form->isSubmitted())
        {
            $min = $form['min']->getData();
            $max = $form['max']->getData();
            $studentsAVG = $repository->SearchByAVG($min,$max);
            return $this->render('student/listStudentsAVG.html.twig',['students'=>$studentsAVG,'form'=>$form->createView(),'notAddmited'=>$studentNotAddmited]);

        }

        return $this->render('student/listStudentsAVG.html.twig',['students'=>$students,'form'=>$form->createView(),'notAddmited'=>$studentNotAddmited]);
    }

}