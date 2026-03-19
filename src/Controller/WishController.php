<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\CategoryRepository;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/wish', name: 'wish')]
final class WishController extends AbstractController
{

    #[Route('/categories', name: '_categories')]
    public function categories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('wish/wishesInCategory.html.twig',['categories' => $categories,]);
    }
    #[Route('/categories/{id}/wishes', name: '_categories_wishes', requirements: ['id' => '\d+'])]
    public function wishesInCategories(CategoryRepository $categoryRepository, int $id): Response
    {
        $category = $categoryRepository->find($id);
        $wishes = $category->getWishes();

        return $this->render('wish/wishesPerCategory.html.twig',['category' => $category,'wishes' => $wishes, 'id'=>$id]);
    }

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


        return $this->render('wish/details.html.twig',['wish' => $wish, 'category' =>$wish->getCategory()], );
    }
    #[Route('/remove/{id}', name: '_remove', requirements: ['id' => '\d+'])]
    public function remove(int $id, WishRepository $wishRepository, EntityManagerInterface $manager): Response
    {

        $wish= $wishRepository->find($id);
        if($wish->getUser() !=$this->getUser() && !$this->isGranted('ROLE_ADMIN')){
            throw $this->createAccessDeniedException("You are not authorized to access this page");
        } else{ $manager->remove($wish);
            $manager->flush();
            $this->addFlash('success', 'Wish has been successfully removed');
        }


        return $this->redirectToRoute('wish_list');
    }

    #[Route('/create', name: '_create', methods: ['POST','GET'], )]
    public function create(EntityManagerInterface $manager, Request $request,): Response
    {
        $wish= new Wish();
        $wish->setUser($this->getUser());
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
        if($wish->getUser()!= $this->getUser()){
            throw $this->createAccessDeniedException("You are not authorized to access this page");
        }
        $wish->setDateUpdated(new \DateTime());
        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);
        if ($wishForm->isSubmitted() && $wishForm->isValid()) {

            $manager->persist($wish);
            $manager->flush();
            $this->addFlash('success', 'Your wish has been successfully updated');
            return $this->redirectToRoute('wish_list_details',['id'=>$wish->getId()]);
        }
        return $this->render('wish/update.html.twig',['wishForm'=>$wishForm]);
    }

}
