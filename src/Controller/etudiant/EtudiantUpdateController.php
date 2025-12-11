<?php

namespace App\Controller\etudiant;

use App\Entity\Etudiant;
use App\Repository\EtudiantRepository;
use App\Repository\TuteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class EtudiantUpdateController extends AbstractController
{
    public function index(
        int $id,
        SessionInterface $session,
        EtudiantRepository $repo
    ): Response {
        // Vérifier connexion
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) {
            return $this->redirect('/login');
        }

        // Récupérer l'étudiant
        $etudiant = $repo->find($id);
        if (!$etudiant || $etudiant->getTuteur()->getId() !== $tuteurId) {
            return $this->redirect('/etudiants'); // interdit
        }

        return $this->render('etudiant/update_etudiant.html.twig', [
            'etudiant' => $etudiant,
        ]);
    }

    public function submit(
        int $id,
        Request $request,
        SessionInterface $session,
        EtudiantRepository $repo,
        EntityManagerInterface $em
    ): Response {
        // Vérifier connexion
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) {
            return $this->redirect('/login');
        }

        // Récupérer l'étudiant
        $etudiant = $repo->find($id);
        if (!$etudiant || $etudiant->getTuteur()->getId() !== $tuteurId) {
            return $this->redirect('/etudiants'); // interdit
        }

        // Récupération POST
        $etudiant->setNom($request->request->get('nom'));
        $etudiant->setPrenom($request->request->get('prenom'));
        $etudiant->setFormation($request->request->get('formation'));

        $em->flush();

        return $this->redirect('/etudiants');
    }
}
