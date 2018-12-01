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
     * @Route("/category/{slug}",
     *     name="category.show",
     *     methods={"GET"},
     *     requirements={"slug":"\w+"}
     *     )
     * @param string $slug
     * @return Response
     */
    public function category(string $slug, Categorie $categorie)
    {
        #######################################################
        # Méthode 1 :
        # $categorie = $this->getDoctrine()
        #                 ->getRepository(Categorie::class)
        #                 ->findOneBy(['slug' => $slug]);
        # $articles = $categorie->getArticles();

        #######################################################
        # Méthode 2 :
        # $articles = $this->getDoctrine()
        #                 ->getRepository(Categorie::class)
        #                 ->findOneBySlug($slug)
        #                 ->getArticles();

        #######################################################
        # Méthode 3 :

        return $this->render("category/category.html.twig", [
            'articles'   =>  $categorie->getArticles(),
            'categorie'  =>  $categorie
        ]);
    }

}