<?php

namespace App\Controller\visite;

use App\Entity\Visite;
use App\Form\VisiteType;
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
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) {
            return $this->redirect('/login');
        }

        $visite = $repo->find($id);
        if (!$visite || $visite->getTuteur()->getId() !== $tuteurId) {
            return $this->redirect('/etudiants');
        }

        $form = $this->createForm(VisiteType::class, $visite);

        return $this->render('visite/update_visite.html.twig', [
            'form'   => $form->createView(),
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
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) {
            return $this->redirect('/login');
        }

        $visite = $repo->find($id);
        if (!$visite || $visite->getTuteur()->getId() !== $tuteurId) {
            return $this->redirect('/etudiants');
        }

        $form = $this->createForm(VisiteType::class, $visite);
        $form->handleRequest($request);

        // inversion des conditions
        if (!($form->isSubmitted() && $form->isValid())) {
            return $this->render('visite/update_visite.html.twig', [
                'form'   => $form->createView(),
                'visite' => $visite
            ]);
        }

        $em->flush();

        $etudiantId = $visite->getEtudiant()->getId();
        return $this->redirect("/etudiants/$etudiantId/visites");
    }
}
