<?php

namespace App\Repository;

use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Sortie $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Sortie $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function recherche ($recherche_mt=null, $idSite=null, $idEtat=null, $dateDeDebut=null, $dateDeFin=null, $organisateur=null, $inscrit=null, $nonInscrit=null, $passee=null){
        $query =$this->createQueryBuilder('sortie')
            ->innerJoin('sortie.site_organisateur', 'site')
            ->innerJoin('sortie.organisateur', 'participant')
            ->innerJoin('sortie.etat','etat')
            ->addSelect('site')
            ->addSelect('participant')
            ->addSelect('etat');

        if ($recherche_mt != null){
            $query->andWhere('sortie.nom_sortie LIKE :recherche_mt')
                ->setParameter('recherche_mt', '%'.$recherche_mt.'%');
        }

        if ($idSite > 0){
            $query->andWhere('site.id = :idSite')
                ->setParameter('idSite', $idSite);
        }

        if ($idEtat > 0){
            $query->andWhere('etat.id = :idEtat')
                ->setParameter('idEtat', $idEtat);
        }


        if ($dateDeDebut !=null){
            $query->andWhere('sortie.date_debut > :dateDeDebut')
                ->setParameter('dateDeDebut', new \DateTime($dateDeDebut));
        }

        if ($dateDeFin !=null){
            $query->andWhere('sortie.date_debut < :dateDeFin')
                ->setParameter('dateDeFin', new \DateTime($dateDeFin));
        }

        if ($organisateur != null){
//            = $user
            $organisateur = $this->getEntityManager()->getRepository(Participant::class)->find($organisateur);
            $query->andWhere('sortie.organisateur = :organisateur')
                ->setParameter('organisateur', $organisateur);
        }

        if($inscrit != null){

            $user = $this->getEntityManager()->getRepository(Participant::class)->find($inscrit);

            $query->andWhere(':inscrit MEMBER OF sortie.participants')

                ->setParameter('inscrit', $user);

        }


        if($nonInscrit != null){

            $user = $this->getEntityManager()->getRepository(Participant::class)->find($nonInscrit);

            $query->andWhere(':inscrit NOT MEMBER OF sortie.participants')

                ->setParameter('inscrit', $user);

        }

        if($passee != null){

            $query->andWhere('etat.libelle = :etat')

                ->setParameter('etat', 'Passée');

        }

        return $query->getQuery()->getResult();
    }


    public function findByUtilisateurSite (int $idSite){
        $query = $this->createQueryBuilder('sortie')
            ->innerJoin('sortie.organisateur', 'participant')
//            ->innerJoin('sortie.inscrits', 'inscription')
            ->addSelect('participant')
//            ->addSelect('inscription');
            ->where('sortie.site_organisateur = :site_organisateur')->setParameter('site_organisateur', $idSite);
//            ->andWhere()

        return $query->getQuery()->getResult();

    }


    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
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
    public function findOneBySomeField($value): ?Sortie
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
