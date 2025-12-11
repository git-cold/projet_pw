<?php

namespace App\Controller\etudiant;

use App\Repository\EtudiantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class EtudiantReadController extends AbstractController
{
    public function index(SessionInterface $session, EtudiantRepository $repo): Response {
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) {
            return $this->redirect('/login');
        }

        $etudiants = $repo->findBy(['tuteur' => $tuteurId]);

        return $this->render('etudiant/read_etudiant.html.twig', [
            'etudiants' => $etudiants
        ]);
    }
}
