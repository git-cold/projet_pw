<?php

namespace App\Controller\auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LogoutController extends AbstractController
{
    public function logout(SessionInterface $session): Response
    {
        $session->remove('tuteur_id');

        return $this->redirect('/login');
    }
}
