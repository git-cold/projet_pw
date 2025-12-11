<?php

namespace App\Controller\visite;

use App\Repository\EtudiantRepository;
use App\Repository\VisiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;

class VisiteReadController extends AbstractController
{
    public function index(
        int $id,
        Request $request,
        SessionInterface $session,
        EtudiantRepository $etudiantRepo,
        VisiteRepository $visiteRepo
    ): Response {
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) {
            return $this->redirect('/login');
        }

        $etudiant = $etudiantRepo->find($id);
        if (!$etudiant || $etudiant->getTuteur()->getId() !== $tuteurId) {
            return $this->redirect('/etudiants');
        }

        // GET params
        $statut = $request->query->get('statut', 'toutes');
        $tri = $request->query->get('tri', 'asc');

        // Repository custom
        $visites = $visiteRepo->findByEtudiantFiltered($id, $statut, $tri);

        return $this->render('read_visite.html.twig', [
            'etudiant' => $etudiant,
            'visites' => $visites,
            'statut' => $statut,
            'tri' => $tri
        ]);
    }
}
