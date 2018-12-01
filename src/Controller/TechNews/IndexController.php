<?php
namespace App\Controller\TechNews;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{
    /**
     * home page
     * @Route("/", name="index")
     *
     * @return Response
     */
    public function index()
    {
        /**
         * Récupération des articles depuis le articles.yaml
         * $articles = $yamlProvider->getArticles();
         */

        /**
         * on va récupérer désormais les articles depuis Doctrine
         */
        $repository = $this->getDoctrine()
            ->getRepository(Article::class);

        $articles   = $repository->findBy([], ["id" => "DESC"]);
        $spotlight  = $repository->findSpotlightArticles();
        $special    = $repository->findSpecialArticles();

        return $this->render('Front/index.html.twig', [
            'articles'   => $articles,
            'spotlight'  => $spotlight,
            'special'    => $special
        ]);
    }

}