<?php

namespace App\Controller\etudiant;

use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class EtudiantDeleteController extends AbstractController
{
    public function delete(
        int $id,
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

        // Si inexistant ou n'appartient pas au tuteur → interdit
        if (!$etudiant || $etudiant->getTuteur()->getId() !== $tuteurId) {
            return $this->redirect('/etudiants');
        }

        // Suppression
        $em->remove($etudiant);
        $em->flush();

        return $this->redirect('/etudiants');
    }
}
