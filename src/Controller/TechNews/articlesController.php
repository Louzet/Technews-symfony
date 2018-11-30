<?php

namespace App\Controller\TechNews;

use App\Article\Provider\YamlProvider;
use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class articlesController extends Controller
{
    /**
     * Allow to display an article from the slug and id
     * @Route("/{category}/{slug}_{id}.{ext}",
     *     name="article.index",
     *     requirements={"category":"\w+", "slug":"^[a-z0-9]+(?:-[a-z0-9]+)*$", "id":"\d+"},
     *     defaults={"ext":"html|php"})
     * @param Article $article
     * @param $idArticle
     * @param $slug
     * @return Response
     */
    public function article($article, $idArticle, $slug)
    {
        # exemple d'URL
        # politique/les-gilets-jaunes-mettent-le-feu-a-l-elysee_135153.html



        $suggestions = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findArticlesSuggestions($article->getId(), $article->getCategorie()->getId())
            ;

        return $this->render("articles/article.html.twig", [
            'suggestions'   =>   $suggestions
        ]);
    }
}