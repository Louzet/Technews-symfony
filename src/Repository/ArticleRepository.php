<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
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
    const MAX_SUGGESTION = 3;

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
            # Tous les articles d'une catégorie ($idCategorie)
            ->where('a.categorie = :category_id')
            ->setParameter('category_id', $idCategory)
            # sauf un article ($idArticle)
            ->andWhere('a.id != :article_id')
            ->setParameter('article_id', $idArticle)
            # 3 Articles maximum
            ->setMaxResults(self::MAX_SUGGESTION)
            # par ordre décroissant
            ->orderBy('a.id', 'DESC')
            # On finalise
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Article
     * Récupérer les articles en spotlight
     */
    public function findSpotlightArticles()
    {
        return $this->createQueryBuilder('a')
                   ->where('a.spotlight = 1')
                   ->orderBy('a.id', 'DESC')
                   ->setMaxResults(self::MAX_RESULT)
                   ->getQuery()
                   ->getResult()
           ;
    }

    /**
     * @return Article
     * Récupérer les articles en special
     */
    public function findSpecialArticles()
    {
        return $this->createQueryBuilder('a')
            ->where('a.special = 1')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(self::MAX_RESULT)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllMyArticles($membreId)
    {
        return $this->createQueryBuilder('a')
            ->addSelect('a')
            ->where('a.membre = :membre_id')
            ->setParameter('membre_id', $membreId)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     * Retourne tous les articles d'un auteur par rapport aux
     * status definis par le workflow
     */
    public function findAuthorArticlesByStatus($idAuteur, $status)
    {
        return $this->createQueryBuilder('a')
            ->where('a.membre = :membre_id')
            ->setParameter('membre_id', $idAuteur)
            ->andWhere('a.status LIKE :status')
            ->setParameter('status', "%$status%")
            ->orderBy('a.datecreation')
            ->getQuery()
            ->getResult()
            ;
    }

    public function countAuthorArticlesByStatus($idAuteur, $status)
    {
        try {
            return $this->createQueryBuilder('a')
                ->addSelect('COUNT(a)')
                ->where('a.membre = :membre_id')
                ->setParameter('membre_id', $idAuteur)
                ->andWhere('a.status LIKE :status')
                ->setParameter('status', "%$status%")
                ->getQuery()
                ->getSingleScalarResult();
        }
        catch (NonUniqueResultException $nonUniqueResultException){
            return 0;
        }
    }


    /**
     * @return Article[] Returns an array of Article objects
     */

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
