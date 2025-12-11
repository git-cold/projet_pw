<?php

namespace App\Controller\visite;

use App\Repository\VisiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;

class VisiteDeleteController extends AbstractController
{
    public function delete(
        int $id,
        SessionInterface $session,
        VisiteRepository $repo,
        EntityManagerInterface $em
    ): Response {

        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) {
            return $this->redirect('/login');
        }

        $visite = $repo->find($id);
        if (!$visite) {
            return $this->redirect('/dashboard');
        }

        // Vérifie que la visite appartient bien au tuteur connecté
        if ($visite->getTuteur()->getId() !== $tuteurId) {
            return $this->redirect('/dashboard');
        }

        $etudiantId = $visite->getEtudiant()->getId();

        $em->remove($visite);
        $em->flush();

        return $this->redirect("/etudiants/$etudiantId/visites");
    }
}
