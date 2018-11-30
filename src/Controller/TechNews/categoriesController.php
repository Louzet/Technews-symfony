<?php

namespace App\Controller\TechNews;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class categoriesController extends Controller
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
    public function category(string $slug)
    {
        return $this->render("category/category.html.twig");
    }

}