<?php
namespace App\Controller\TechNews;

use App\Article\Provider\YamlProvider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class indexController extends Controller
{
    /**
     * home page
     * @Route("/", name="index")
     * @param YamlProvider $yamlProvider
     * @return Response
     */
    public function index(YamlProvider $yamlProvider)
    {
        /**
         * Récupération des articles depuis le articles.yaml
         */
        $articles = $yamlProvider->getArticles();

        return $this->render('Front/index.html.twig', [
            'articles' => $articles
        ]);
    }


}