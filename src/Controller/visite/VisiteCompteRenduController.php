<?php

namespace App\Controller\visite;

use App\Repository\VisiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class VisiteCompteRenduController extends AbstractController
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

        return $this->render('visite/compte_rendu_visite.html.twig', [
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

        $compteRendu = $request->request->get('compteRendu');
        $visite->setCompteRendu($compteRendu);

        $em->flush();

        return $this->redirect("/visites/$id/compte-rendu");
    }

    public function pdf(
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

        $html = $this->renderView('visite/compte_rendu_visite_pdf.html.twig', [
            'visite' => $visite
        ]);

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream('visite_'.$id.'.pdf', [
            "Attachment" => true
        ]);

        exit;
    }
}
