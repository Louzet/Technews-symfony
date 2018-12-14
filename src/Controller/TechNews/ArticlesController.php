<?php

namespace App\Controller\TechNews;

use App\Article\ArticleType;
use App\Controller\TransliteratorSlugTrait;
use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Membre;
use App\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticlesController
 * @package App\Controller\TechNews
 */
class ArticlesController extends AbstractController
{
    use TransliteratorSlugTrait;

    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * ArticlesController constructor.
     * @param ArticleRepository $articleRepository
     */
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * Allow to display an article from the slug and id
     * @Route("/{categorie}/{slug}_{id}.{ext}",
     *     name="article.index",
     *     requirements={"category":"\w+",
     *     "slug":"^[a-z0-9]+(?:-[a-z0-9]+)*$",
     *     "id":"\d+"},
     *     defaults={"ext":"html|php"}
     * )
     * @param Article $article
     * @param $id
     * @param $slug
     * @param $categorie
     * @return Response
     */
    public function article(Article $article = null, $id, $slug, $categorie)
    {
        # exemple d'URL
        # politique/les-gilets-jaunes-mettent-le-feu-a-l-elysee_135153.html

        if (null === $article) {
            # on redirige l'utilisateur sur la page index
            /* throw $this->createNotFoundException(
                 "Nous n'avons pas trouvé l'article associé à l'id : " .$id
             );*/
            return $this->redirectToRoute('home', [], Response::HTTP_MOVED_PERMANENTLY);
        }

        # on verifie le slug
        if ($article->getSlug() !== $slug || $article->getCategorie()->getSlug() !== $categorie) {
            return $this->redirectToRoute('article.index', [
                'categorie' => $article->getCategorie()->getSlug(),
                'slug' => $article->getSlug(),
                'id' => $id
            ]);
        }

        $suggestions = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findArticlesSuggestions($article->getId(), $article->getCategorie()->getId());

        return $this->render("articles/article.html.twig", [
            'article' => $article,
            'suggestions' => $suggestions
        ]);
    }

    /**
     * Permet de créer un nouvel article
     * @Route("/article/creer-un-article",
     * name="article.new")
     * @param Request $request
     * @Security("has_role('ROLE_AUTEUR')")
     * @return Response
     */
    public function newArticle(Request $request)
    {
        # $membre = $this->getDoctrine()->getRepository(Membre::class)->find(2);

        $article = new Article();

        # on insère le membre récuperé en session
        $article->setMembre($this->getUser());

        $form = $this->createForm(ArticleType::class, $article)->handleRequest($request);

        # On vérifie si le formulaire est soumis et est valide
        if($form->isSubmitted() && $form->isValid())
        {
            # Traitement de l'upload de l'image
            /**
             * @var UploadedFile $featuredImage
             */
            $featuredImage = $article->getFeaturedImage();
            $file          = $this->slugify($article->getTitre()).'.'.$featuredImage->guessExtension();

            try{
                $featuredImage->move($this->getParameter('articles_assets_dir'), $file);
            }catch (FileException $exception){
                throw new FileException("Impossible de télécharger l'image");
            }

            # Mise à jour de l'image
            $article->setFeaturedImage($file);

            # Mise à jour du slug
            $article->setSlug($this->slugify($article->getTitre()));

            # Sauvegarde en BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            # Notification
            $this->addFlash(
                'notice',
                'Félicitation votre article est enregistré !'
            );

            # Redirection vers l'article crée
            return $this->redirectToRoute("article.index", [
                "categorie"   => $article->getCategorie()->getSlug(),
                "slug"        => $article->getSlug(),
                "id"          => $article->getId()
            ]);
        }

        # affichage du formulaire
        return $this->render('articles/form.html.twig', [
            'form'  => $form->createView()
        ]);

    }

    /**
     * Affiche la liste de tous les articles déjà crées
     * par un utilisateur
     * @Route("/article", name="article.list")
     */
    public function listArticle()
    {
        $articles = $this->articleRepository->findAllMyArticles($this->getUser());

        return $this->render('articles/list.html.twig', [
            'articles'   => $articles
        ]);
    }

    /**
     * @Route("/article/modifier-un-article/{id}", name="article.update",
     *     requirements={"id":"\d+"}
     * )
     * on verifie que l'utilisateur est l'auteur de cette article
     * @Security("article.isAuteur(user)")
     * @param Request $request
     * @param Article $article
     * @param Packages $packages
     * @return Response
     */
    public function editArticle(Request $request, Article $article, Packages $packages)
    {
        $options = [
            'image_url' => $packages->getUrl('images/products/'.$article->getFeaturedImage())
        ];

        #Récupération de l'image de base
        $featuredImageDefault = $article->getFeaturedImage();

        $article->setFeaturedImage(
            new File($this->getParameter('articles_assets_dir').'/'.$article->getFeaturedImage())
        );

        $form = $this->createForm(ArticleType::class, $article, $options)
                     ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            #dump($article);
            # 1. Traitement de l'upload de l'image

            /**
             * récupération de la nouvelle image, si elle a été modifiée
             * @var UploadedFile $featuredImage
             */
            $featuredImage = $article->getFeaturedImage();

            if (null !== $featuredImage) {

                $fileName = $this->slugify($article->getTitre())
                    . '.' . $featuredImage->guessExtension();

                try {
                    $featuredImage->move(
                        $this->getParameter('articles_assets_dir'),
                        $fileName
                    );
                } catch (FileException $e) {

                }

                # Mise à jour de l'image
                $article->setFeaturedImage($fileName);

            } else {
                $article->setFeaturedImage($featuredImageDefault);
            }

            # 2. Mise à jour du Slug
            $article->setSlug($this->slugify($article->getTitre()));

            # 3. Sauvegarde en BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            # 4. Notification
            $this->addFlash('notice',
                'Félicitation, votre article est en ligne !');

            # 5. Redirection vers l'article créé
            return $this->redirectToRoute('article.list', [
                'id' => $article->getId()
            ]);
        }

        return $this->render('articles/update.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * Démonstration de l'ajout
     * d'un article avec Doctrine.
     * @Route("test/ajouter-un-article",
     *     name="article.test")
     */
    public function test()
    {
        # Création d'une Catégorie
        $categorie = new Categorie();
        $categorie->setNom('Mode');
        $categorie->setSlug('mode');

        # Création d'un Membre (Auteur de l'article)
        $membre = new Membre();
        $membre
            ->setPrenom('Mickael')
            ->setNom('Louzet')
            ->setEmail('mick_louzet@tech.news')
            ->setPassword('test')
            ->setRoles(['ROLE_AUTEUR']);

        # Création de l'article
        $article = new Article();
        $article->setTitre("Blond cendré Tendance 2017: Couleur de cheveux pour les femmes romantiques");
        $article->setSlug("blond-cendre-tendance-2017-couleur-de-cheveux-pour-les-femmes-romantiques");
        $article->setContent("Froid mais élégant, le blond cendré c’est la couleur chic qui sied parfaitement 
        aux beautés nordiques à la peau claire et aux yeux bleus. 
        Zoom sur une coloration très tendance en 2017 : le blond cendré, Comment porter cette coloration tendre et
        naturelle et comment l’entretenir.");
        $article->setFeaturedImage("16164504.jpg");
        $article->setSpotlight(0);
        $article->setSpecial(1);
        $article->setCategorie($categorie);
        $article->setMembre($membre);


        # On sauvegarde le tout avec Doctrine
        $em = $this->getDoctrine()->getManager();
        $em->persist($categorie);
        $em->persist($membre);
        $em->persist($article);
        $em->flush();
        # Affichage d'une réponse.
        return new Response(
            'Nouvel Article ID : '
            . $article->getId()
            . ' dans la catégorie : '
            . $categorie->getNom()
            . ' de l\'auteur : '
            . $membre->getPrenom()
        );
    }


}
