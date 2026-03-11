<?php

namespace App\Controller;

use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/wish', name: 'wish')]
final class WishController extends AbstractController
{


    #[Route('/list', name: '_list')]
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findAll();
        return $this->render('wish/list.html.twig',['wishList' => $wishes]);
    }
    #[Route('/list/{id}', name: '_list_details', requirements: ['id' => '\d+'])]
    public function details(int $id, WishRepository $wishRepository): Response
    {
        $wish= $wishRepository->findOneBy(['id'=>$id]);


        return $this->render('wish/details.html.twig',['wish' => $wish]);
    }

    #[Route('/create', name: '_create', methods: ['POST','GET'])]
    public function create(): Response
    {
        // création d'un formulaire de création
        return $this->render('wish/create.html.twig');

    }
}
