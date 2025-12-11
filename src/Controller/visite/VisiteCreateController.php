<?php

namespace App\Controller\visite;

use App\Entity\Visite;
use App\Form\VisiteType;
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
        EtudiantRepository $etudiantRepo,
        TuteurRepository $tuteurRepo
    ): Response {
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) {
            return $this->redirect('/login');
        }

        $etudiant = $etudiantRepo->find($id);
        if (!$etudiant || $etudiant->getTuteur()->getId() !== $tuteurId) {
            return $this->redirect('/etudiants');
        }

        $tuteurConnecte = $tuteurRepo->find($tuteurId);

        $visite = new Visite();
        $visite->setEtudiant($etudiant);
        $visite->setTuteur($tuteurConnecte);
        $visite->setStatut('prévue');

        $form = $this->createForm(VisiteType::class, $visite);

        return $this->render('visite/create_visite.html.twig', [
            'form'     => $form->createView(),
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
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) {
            return $this->redirect('/login');
        }

        $etudiant = $etudiantRepo->find($id);
        if (!$etudiant || $etudiant->getTuteur()->getId() !== $tuteurId) {
            return $this->redirect('/etudiants');
        }

        $tuteurConnecte = $tuteurRepo->find($tuteurId);

        $visite = new Visite();
        $visite->setEtudiant($etudiant);
        $visite->setTuteur($tuteurConnecte);
        $visite->setStatut('prévue');

        $form = $this->createForm(VisiteType::class, $visite);
        $form->handleRequest($request);

        if (!($form->isSubmitted() && $form->isValid())) {
            return $this->render('visite/create_visite.html.twig', [
                'form'     => $form->createView(),
                'etudiant' => $etudiant
            ]);
        }

        $em->persist($visite);
        $em->flush();

        return $this->redirect("/etudiants/$id/visites");
    }
}
