<?php

namespace App\Controller\TechNews\security;


use App\Membre\MembreLoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/connexion", name="membre.connexion")
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function connexion(AuthenticationUtils $authenticationUtils): Response
    {
        if($this->getUser()){
            #Si l'utilisateur est déjà connecté, on le redirige
            return $this->redirectToRoute('home');
        }
        # Récupération du formulaire de connexion
        $form = $this->createForm(MembreLoginType::class, [
            # Dernier email saisie par l'utilisateur.
            'email'  => $authenticationUtils->getLastUsername()
        ]);

        # Récupération du message d'erreur s'il y en a un.
        $error = $authenticationUtils->getLastAuthenticationError();

        # Affichage du Formulaire
        return $this->render('membre/connexion.html.twig', [
            'form'       => $form->createView(),
            'error'      => $error
        ]);
    }

    /**
     * déconnexion d'un membre
     * @Route("/deconnexion", name="membre.deconnexion")
     */
    public function deconnexion()
    {

    }

    /**
     * On pourrait définir encore d'autres méthodes comme
     * mot de passe oublié, identifiant oublié, etc...
     */

}