<?php

namespace App\Controller\dashboard;

use App\Repository\TuteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DashboardController extends AbstractController
{
    public function index(SessionInterface $session, TuteurRepository $repo): Response {
        // Récupération de l'id tuteur
        $id = $session->get('tuteur_id');

        if (!$id) {
            return $this->redirect('/login');
        }

        // Tuteur + relations
        $tuteur = $repo->find($id);

        if (!$tuteur) {
            return $this->redirect('/login');
        }

        // Relations ORM (si mappées)
        $etudiants = $tuteur->getEtudiants();  
        $visites = $tuteur->getVisites();

        return $this->render('dashboard/dashboard.html.twig', [
            'tuteur' => $tuteur,
            'etudiants' => $etudiants,
            'visites' => $visites,
        ]);
    }
}
