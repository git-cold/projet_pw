<?php

namespace App\Controller\visite;

use App\Entity\Visite;
use App\Repository\EtudiantRepository;
use App\Repository\TuteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class VisiteCreateController extends AbstractController
{
    public function index(
        int $id,
        SessionInterface $session,
        EtudiantRepository $etudiantRepo
    ): Response {
        // Vérifier si tuteur connecté
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) {
            return $this->redirect('/login');
        }

        // Vérifier que l'étudiant existe + appartient bien au tuteur
        $etudiant = $etudiantRepo->find($id);
        if (!$etudiant || $etudiant->getTuteur()->getId() !== $tuteurId) {
            return $this->redirect('/etudiants');
        }

        return $this->render('visite/create_visite.html.twig', [
            'etudiant' => $etudiant
        ]);
    }

    public function submit(
        int $id,
        Request $request,
        SessionInterface $session,
        EtudiantRepository $etudiantRepo,
        TuteurRepository $tuteurRepo,
        EntityManagerInterface $em
    ): Response {
        // Vérifier si tuteur connecté
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) {
            return $this->redirect('/login');
        }

        $tuteurConnecte = $tuteurRepo->find($tuteurId);

        // Vérifier étudiant
        $etudiant = $etudiantRepo->find($id);
        if (!$etudiant || $etudiant->getTuteur()->getId() !== $tuteurId) {
            return $this->redirect('/etudiants');
        }

        // Récupération POST
        $date = $request->request->get('date');
        $commentaire = $request->request->get('commentaire');
        $statut = $request->request->get('statut');

        // Sécurisation du statut
        if (!in_array($statut, ['prévue', 'réalisée', 'annulée'])) {
            $statut = 'prévue';
        }

        // Création visite
        $visite = new Visite();
        $visite->setDate(new \DateTimeImmutable($date));
        $visite->setCommentaire($commentaire);
        $visite->setStatut($statut);

        // Préremplissage automatique
        $visite->setEtudiant($etudiant);
        $visite->setTuteur($tuteurConnecte);

        // Sauvegarde
        $em->persist($visite);
        $em->flush();

        return $this->redirect("/etudiants/$id/visites");
    }
}
