<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    const MAX_RESULT = 5;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * Renvoie les derniers commentaires en date
     * @return mixed
     */
    public function findLatestsArticles()
    {
        return $this->createQueryBuilder('a')
                    ->addOrderBy('a.datecreation', 'DESC')
                    ->setMaxResults(self::MAX_RESULT)
                    ->getQuery()
                    ->getResult()
            ;
    }


    /**
     * @param $idArticle
     * @param $idCategory
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findArticlesSuggestions($idArticle, $idCategory)
    {
        return $this->createQueryBuilder('a')
                    ->addGroupBy('a.categorie')
                    ->where('a.id' != $idArticle)
                    ->join('a.categorie', $idCategory)
                    ->setMaxResults(self::MAX_RESULT - 2)
                    ->addGroupBy('a.datecreation', 'DESC')
                    ->getQuery()
                    ->getResult()
            ;
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
