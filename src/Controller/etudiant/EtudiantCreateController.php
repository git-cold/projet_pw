<?php

namespace App\Controller\etudiant;

use App\Entity\Etudiant;
use App\Repository\TuteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class EtudiantCreateController extends AbstractController
{
    public function index(SessionInterface $session): Response {
        if (!$session->get('tuteur_id')) {
            return $this->redirect('/login');
        }

        return $this->render('etudiant/create_etudiant.html.twig');
    }

    public function submit(
        Request $request,
        SessionInterface $session,
        TuteurRepository $tuteurRepo,
        EntityManagerInterface $em
    ): Response {
        // Vérifier connexion
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) {
            return $this->redirect('/login');
        }

        $tuteurConnecte = $tuteurRepo->find($tuteurId);

        // Récupération POST
        $nom = $request->request->get('nom');
        $prenom = $request->request->get('prenom');
        $formation = $request->request->get('formation');

        // Création
        $etudiant = new Etudiant();
        $etudiant->setNom($nom);
        $etudiant->setPrenom($prenom);
        $etudiant->setFormation($formation);
        $etudiant->setTuteur($tuteurConnecte);

        // Sauvegarde
        $em->persist($etudiant);
        $em->flush();

        return $this->redirect('/etudiants');
    }
}
