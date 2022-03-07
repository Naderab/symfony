<?php

namespace App\Repository;

use App\Entity\Classroom;
use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    public function listStudentOrdredByEmail()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.email','DESC')
            ->getQuery()
            ->getResult();
    }

    public function searchByNsc($nsc)
    {
        return $this->createQueryBuilder('s')
            ->where('s.nsc LIKE :nsc')
            ->setParameter('nsc',$nsc)
            ->getQuery()
            ->getResult();
    }

    public function listStudentByClassroom($id){
        return $this->createQueryBuilder('s')
            ->join('s.classroom','c')
            ->addSelect('c')
            ->where('c.id = :id')
            ->setParameter('id',$id)
            ->getQuery()
            ->getResult();
    }

    public function SearchByAVG($min,$max){
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT s FROM App\Entity\Student s WHERE s.average BETWEEN :min AND :max')
            ->setParameter('min',$min)
            ->setParameter('max',$max);
        return $query->getResult();

    }

    public function studentsNotAddmited()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT s FROM App\Entity\Student s WHERE s.average <= 8');
        return $query->getResult();
    }
    // /**
    //  * @return Student[] Returns an array of Student objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Student
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
