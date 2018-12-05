<?php

namespace App\Controller\TechNews;

use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends Controller
{
    /**
     * Allow to display the articles from one category
     * @Route("/categorie/{slug}",
     *     name="categorie.index",
     *     methods={"GET"},
     *     requirements={"slug":"\w+"}
     *     )
     * @param Categorie $categorie
     * @return Response
     */
    public function category(Categorie $categorie = null, $slug)
    {
        #######################################################
        # Méthode 1 :
        $categorie = $this->getDoctrine()
                        ->getRepository(Categorie::class)
                        ->findOneBy(['slug' => $slug]);
        $articles = $categorie->getArticles();

        #######################################################
        # Méthode 2 :
        # $articles = $this->getDoctrine()
        #                 ->getRepository(Categorie::class)
        #                 ->findOneBySlug($slug)
        #                 ->getArticles();

        #######################################################
        # Méthode 3 :
        if (null === $categorie) {
            // On redirige l'utilisateur sur la page d'accueil
            return $this->redirectToRoute('home', [], Response::HTTP_MOVED_PERMANENTLY);
        }


        return $this->render("category/category.html.twig", [
            'articles'   =>  $articles,
            'categorie'  =>  $categorie
        ]);
    }
}
