<?php

namespace App\Controller\visite;

use App\Repository\VisiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class VisiteUpdateController extends AbstractController
{
    public function index(
        int $id,
        SessionInterface $session,
        VisiteRepository $repo
    ): Response {
        // Vérif connexion
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) {
            return $this->redirect('/login');
        }

        // Récupérer visite
        $visite = $repo->find($id);

        // Vérif qu'elle existe et qu'elle appartient au tuteur connecté
        if (!$visite || $visite->getTuteur()->getId() !== $tuteurId) {
            return $this->redirect('/etudiants');
        }

        return $this->render('visite/update_visite.html.twig', [
            'visite' => $visite
        ]);
    }

    public function submit(
        int $id,
        Request $request,
        SessionInterface $session,
        VisiteRepository $repo,
        EntityManagerInterface $em
    ): Response {
        // Vérif connexion
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) {
            return $this->redirect('/login');
        }

        // Récup visite
        $visite = $repo->find($id);

        if (!$visite || $visite->getTuteur()->getId() !== $tuteurId) {
            return $this->redirect('/etudiants');
        }

        // Récupération des données POST
        $date = $request->request->get('date');
        $commentaire = $request->request->get('commentaire');
        $statut = $request->request->get('statut');

        // Sécurisation du statut
        if (!in_array($statut, ['prévue', 'réalisée', 'annulée'])) {
            $statut = 'prévue';
        }

        // Mise à jour
        $visite->setDate(new \DateTimeImmutable($date));
        $visite->setCommentaire($commentaire);
        $visite->setStatut($statut);

        $em->flush();

        // Rediriger vers les visites de l’étudiant
        $etudiantId = $visite->getEtudiant()->getId();
        return $this->redirect("/etudiants/$etudiantId/visites");
    }
}
