<?php

namespace App\Repository;

use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
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


    /**
     * @param $recherche_mt
     * @param $idSite
     * @param $idEtat
     * @param $dateDeDebut
     * @param $dateDeFin
     * @param $organisateur
     * @param $inscrit
     * @param $nonInscrit
     * @param $passee
     * @return float|int|mixed|string
     * @throws \Exception
     */
    public function recherche ($recherche_mt=null, $idSite=null, $idEtat=null, $dateDeDebut=null, $dateDeFin=null, $organisateur=null, $inscrit=null, $nonInscrit=null, $passee=null){

        //requête queryBuilder qui permet de récupérer des sortie en fonction d'une ou plusieur
        //proprièter parmis celle si dessous
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
            $query->andWhere(':inscrit MEMBER OF sortie.inscrits')

                ->setParameter('inscrit', $user);

        }


        if($nonInscrit != null){

            $user = $this->getEntityManager()->getRepository(Participant::class)->find($nonInscrit);
            $query->andWhere(':inscrit NOT MEMBER OF sortie.inscrits')

                ->setParameter('inscrit', $user);

        }

        if($passee != null){

            $query->andWhere('etat.libelle = :etat')
                ->setParameter('etat', 'Passée');

        }

        return $query->getQuery()->getResult();
    }


    /**
     * @param int $idSite
     * @return float|int|mixed|string
     */
    public function findByUtilisateurSite (int $idSite){

        //requête queryBuilder qui permet de récuperer des sortie en fonction de la proprièter site_organisateur
        $query = $this->createQueryBuilder('sortie')
            ->innerJoin('sortie.organisateur', 'participant')
            ->addSelect('participant')
            ->where('sortie.site_organisateur = :site_organisateur')->setParameter('site_organisateur', $idSite);

        return $query->getQuery()->getResult();


    }

    /**
     * @return float|int|mixed|string
     */
    public function findByDateDESC(){

        //requête queryBuilder qui récupère tout les sortie et les class par ordre décroissant en fonction
        //de la proprièter date_debut
        $query = $this->createQueryBuilder('sortie')
            ->innerJoin('sortie.organisateur', 'participant')
            ->addSelect('participant')
            ->orderBy('sortie.date_debut', 'DESC');

        return $query->getQuery()->getResult();

    }

    /**
     * SELECT * FROM sorties where id = ?
     * @param int $id
     * @return Sortie/null
     */
    public function findById(int $id): ?Sortie {

        try {
            $query = $this->createQueryBuilder('sortie')
                ->Where('sortie.id = :id')->setParameter('id', $id);

            return $query->getQuery()->getOneOrNullResult();

        } catch (NonUniqueResultException $exception) {
            return null;
        }
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
