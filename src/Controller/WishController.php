<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    #[Route('/remove/{id}', name: '_remove', requirements: ['id' => '\d+'])]
    public function remove(int $id, WishRepository $wishRepository, EntityManagerInterface $manager): Response
    {
        $wish= $wishRepository->find($id);

            $manager->remove($wish);
            $manager->flush();
        $this->addFlash('success', 'Wish has been successfully removed');
        return $this->redirectToRoute('wish_list');
    }

    #[Route('/create', name: '_create', methods: ['POST','GET'])]
    public function create(EntityManagerInterface $manager, Request $request,): Response
    {
        $wish= new Wish();
        $wish->setIsPublished(true);
        $wish->setDateCreated(new \DateTime());
        $wish->setDateUpdated(null);
        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);
        if ($wishForm->isSubmitted() && $wishForm->isValid()) {

            $manager->persist($wish);
            $manager->flush();
            $this->addFlash('success', 'Your wish has been successfully created');
            return $this->redirectToRoute('wish_list_details',['id'=>$wish->getId()]);
        }
        return $this->render('wish/create.html.twig',['wishForm'=>$wishForm]);

    }
    #[Route('/update/{id}', name: '_update', methods: ['POST','GET'])]
    public function update(int $id, WishRepository $repository,EntityManagerInterface $manager, Request $request,): Response
    {
        $wish= $repository->find($id);
        $wish->setDateUpdated(new \DateTime());
        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);
        if ($wishForm->isSubmitted() && $wishForm->isValid()) {

            $manager->persist($wish);
            $manager->flush();
            $this->addFlash('success', 'Your wish has been successfully created');
            return $this->redirectToRoute('wish_list_details',['id'=>$wish->getId()]);
        }
        return $this->render('wish/update.html.twig',['wishForm'=>$wishForm]);

    }
}
