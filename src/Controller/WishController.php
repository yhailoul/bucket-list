<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/wish', name: 'wish')]
final class WishController extends AbstractController
{


    #[Route('/list', name: '_list')]
    public function list(): Response
    {

        // renvoyer la liste
        return $this->render('wish/list.html.twig');
    }
    #[Route('/{id}', name: '_list_details', requirements: ['id' => '\d+'])]
    public function details(int $id): Response
    {

        dump($id);
        // renvoyer le détail d'une liste
        return $this->render('wish/details.html.twig');
    }

    #[Route('/create', name: '_create', methods: ['POST','GET'])]
    public function create(): Response
    {
        // création d'un formulaire de création
        return $this->render('wish/create.html.twig');

    }
}
