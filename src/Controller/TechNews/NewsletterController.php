<?php

namespace App\Controller\TechNews;

use App\Entity\Newsletter;
use App\Newsletter\NewsletterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class NewsletterController
 * @package App\Controller\TechNews
 */
class NewsletterController extends AbstractController
{
    /**
     * Affiche le modal de souscription à la newsletter
     * @param Newsletter $newsletter
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function Newsletter(Newsletter $newsletter)
    {
        $form = $this->createForm(NewsletterType::class);
        /*if(!$this->getUser() && !$newsletter->getEmail()){




            if($form->isSubmitted() && $form->isValid()){

            }
        }*/


        return $this->render('newsletter/_modal.html.twig', [
            'form'  => $form->createView()
        ]);
    }

}