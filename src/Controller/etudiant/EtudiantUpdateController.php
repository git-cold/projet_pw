<?php

namespace App\Controller\etudiant;

use App\Entity\Etudiant;
use App\Form\EtudiantType;
use App\Repository\EtudiantRepository;
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
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) {
            return $this->redirect('/login');
        }

        $etudiant = $repo->find($id);
        if (!$etudiant || $etudiant->getTuteur()->getId() !== $tuteurId) {
            return $this->redirect('/etudiants');
        }

        $form = $this->createForm(EtudiantType::class, $etudiant);

        return $this->render('etudiant/update_etudiant.html.twig', [
            'form' => $form->createView(),
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
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) {
            return $this->redirect('/login');
        }

        $etudiant = $repo->find($id);
        if (!$etudiant || $etudiant->getTuteur()->getId() !== $tuteurId) {
            return $this->redirect('/etudiants');
        }

        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if (!($form->isSubmitted() && $form->isValid())) {
            return $this->render('etudiant/update_etudiant.html.twig', [
                'form' => $form->createView(),
                'etudiant' => $etudiant
            ]);
        }

        $em->flush();

        return $this->redirect('/etudiants');
    }
}
