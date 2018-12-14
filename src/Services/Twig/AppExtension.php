<?php
namespace App\Services\Twig;

use App\Entity\Article;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{
    private $em;

    private $session;

    const NB_SUMMARY_CHAR = 200;

    private $membre;

    /**
     * AppExtension constructor.
     * @param EntityManagerInterface $manager
     * @param SessionInterface $session
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(EntityManagerInterface $manager, SessionInterface $session, TokenStorageInterface $tokenStorage)
    {
        $this->em = $manager;

        $this->session = $session;

        # récupération d'un membre si un token existe
        if($tokenStorage->getToken())
        {
            $this->membre  = $tokenStorage->getToken()->getUser();
        }
    }


    public function getFunctions()
    {
        return [
            new \Twig_Function('list_all', [$this, 'getCategorieFunction'], ['is_safe' =>['html']]),
            new \Twig_Function('isUserInvited', [$this, 'isUserInvitedFunction']),
            new \Twig_Function('pendingArticles', [$this, 'pendingArticlesFunction'])
        ];
    }

    public function getFilters()
    {
        return [
            new \Twig_Filter('summary', [$this, 'getSummaryFilter'], ['is_safe' => ['html']])
        ];
    }

    public function getCategorieFunction()
    {
        return $this->em->getRepository(Categorie::class)->findCategoriesHavingArticles();

    }

    public function isUserInvitedFunction()
    {
        return $this->session->get('invitedUserModal');
    }

    public function pendingArticlesFunction()
    {
        return $this->em->getRepository(Article::class)
            ->countAuthorArticlesByStatus($this->membre->getId(), 'review');
    }

    public function getSummaryFilter(string $text)
    {
        # Suppression des Balises HTML
        $string = strip_tags($text);

        # Si mon string est supérieur à 170, je continue
        if(strlen($string) > self::NB_SUMMARY_CHAR) {

            # Je coupe ma chaine à 170
            $stringCut = substr($string, 0, self::NB_SUMMARY_CHAR);

            $string = substr($stringCut, 0, strrpos($stringCut, ' ')) . '...';

        }
        return $string;
    }


}