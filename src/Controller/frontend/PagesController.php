<?php

namespace App\Controller\frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function homePage(): Response
    {
        return $this->render('frontend/pages/home.html.twig');
    }


    /**
     * @Route("/a-propos", name="about")
     */
    public function aboutPage(): Response
    {
        return $this->render('frontend/pages/about.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     *
     */
    public function contactPage(): Response
    {
        return $this->render('frontend/pages/contact.html.twig');
    }
}
