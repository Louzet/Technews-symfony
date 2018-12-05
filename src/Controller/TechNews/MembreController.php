<?php
namespace App\Controller\TechNews;

use App\Entity\Membre;
use App\Membre\MembreType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MembreController extends AbstractController
{
    /**
     * @Route("/inscription",
     *     name="membre.inscription",
     *     methods={"GET", "POST"}
     * )
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function Inscription(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        # Création d'un utilisateur
        $membre = new Membre();

        # Création du formulaire
        $form = $this->createForm(MembreType::class, $membre)
            ->handleRequest($request);

        # Si le formulaire est soumis et qu'il est valide
        if($form->isSubmitted() && $form->isValid())
        {
            $membre->setPassword($passwordEncoder->encodePassword($membre, $membre->getPassword()));

            # Sauvegarde en BDD8
            $em = $this->getDoctrine()->getManager();
            $em->persist($membre);
            $em->flush();

            # Notification
            $this->addFlash(
                "notice",
                "Félicitation, vous pouvez maintenant vous connecter"
            );

            # Redirection vers la page de connexion
            return $this->redirectToRoute("home");
        }

        return $this->render("membre/inscription.html.twig", [
            'form'  => $form->createView()
        ]);
    }
}