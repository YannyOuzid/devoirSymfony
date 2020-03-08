<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;
use App\Entity\Tache;
use App\Form\UtilisateurType;
use App\Form\ValidtacheType;
use Symfony\Component\HttpFoundation\Request;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/", name="utilisateur")
     */
    public function index(Request $request)
    {
        $pdo = $this->getDoctrine()->getManager();

        $utilisateur = new Utilisateur();

        $utilisateur->setDateInscription(new \DateTime('now'));

        $form = $this->createForm(UtilisateurType::class, $utilisateur);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $pdo->persist($utilisateur);
            $pdo->flush();
        }

        $utilisateurs = $pdo->getRepository(Utilisateur::class)->findAll();

        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
            'form_ajout' => $form->createView(),
        ]);
    }

     /**
     * @Route("/utilisateur/{id}", name="edit_utilisateur")
     */


    public function edit(Utilisateur $utilisateur, Request $request){

        $pdo = $this->getDoctrine()->getManager();

        $form = $this->createForm(UtilisateurType::class, $utilisateur);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->persist($utilisateur);
            $pdo->flush();
        }
        
        $taches = $pdo->getRepository(Tache::class)->findBy(
            array('utilisateur' =>  $utilisateur));

        return $this->render('utilisateur/utilisateur.html.twig', [
            'utilisateur' => $utilisateur,
            'taches' => $taches,
            'form_edit' => $form->createView(),
            
        ]);

    }


    

    

    
}
