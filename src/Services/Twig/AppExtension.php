<?php
namespace App\Services\Twig;

use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig_Function;


class AppExtension extends AbstractExtension
{
    private $em;

    const NB_SUMMARY_CHAR = 170;

    /**
     * AppExtension constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->em = $manager;
    }

    /**
     * @return array|Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_Function('list_all', [$this, 'getCategorieFunction'], ['is_safe' =>['html']])
        ];
    }

    /**
     * @return array|TwigFilter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter('summary', [$this, 'getSummaryFilter'], ['is_safe' => ['html']])
        ];
    }

    public function getCategorieFunction()
    {
        return $this->em->getRepository(Categorie::class)->findCategoriesHavingArticles();

    }

    public function getSummaryFilter(string $text)
    {
        # Suppression des balises HTML
        $string = strip_tags($text);

        # si une string est superieur à 170 charactères
        if(mb_strlen($string) > self::NB_SUMMARY_CHAR){

            # on coupe la chaine à 170
            $stringCut = substr($string, 0, self::NB_SUMMARY_CHAR);

            $string = substr($stringCut, 0, strpos($stringCut, ' ')). '...';
        }
        return $string;

    }


}