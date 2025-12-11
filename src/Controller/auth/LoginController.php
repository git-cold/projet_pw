<?php

namespace App\Controller\auth;

use App\Repository\TuteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LoginController extends AbstractController
{
    public function index(): Response {
        return $this->render('auth/login.html.twig', ['error' => null]);
    }

    public function submit(
        Request $request,
        TuteurRepository $repo,
        SessionInterface $session
    ): Response {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $tuteur = $repo->findOneBy(['email' => $email]);

        if (! $tuteur) {
            return $this->render('auth/login.html.twig', [
                'error' => 'Identifiants incorrects'
            ]);
        }

        // Vérification du hash
        if (! password_verify($password, $tuteur->getPassword())) {
            return $this->render('auth/login.html.twig', [
                'error' => 'Mot de passe incorrect'
            ]);
        }

        $session->set('tuteur_id', $tuteur->getId());

        return $this->redirect('/dashboard');
    }
}
