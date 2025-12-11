<?php

namespace App\Controller\etudiant;

use App\Entity\Etudiant;
use App\Form\EtudiantType;
use App\Repository\TuteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class EtudiantCreateController extends AbstractController
{
    // GET : afficher le formulaire
    public function index(
        SessionInterface $session
    ): Response {
        if (!$session->get('tuteur_id')) {
            return $this->redirect('/login');
        }

        $form = $this->createForm(EtudiantType::class);

        return $this->render('etudiant/create_etudiant.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // POST : traiter le formulaire
    public function submit(
        Request $request,
        SessionInterface $session,
        TuteurRepository $tuteurRepo,
        EntityManagerInterface $em
    ): Response {
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) {
            return $this->redirect('/login');
        }

        $tuteurConnecte = $tuteurRepo->find($tuteurId);

        $etudiant = new Etudiant();
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('etudiant/create_etudiant.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        $etudiant->setTuteur($tuteurConnecte);

        $em->persist($etudiant);
        $em->flush();

        return $this->redirect('/etudiants');

    }
}
