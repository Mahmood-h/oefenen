<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Form\GenreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class GenreController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $genres = $doctrine->getRepository(Genre::class)->findAll();


        return $this->render('home.html.twig', [
            'genres' => $genres,
        ]);
    }

    #[Route('/genre/{id}', name: 'genre')]
    public function genre(ManagerRegistry $doctrine,int $id): Response
    {
        $genre = $doctrine->getRepository(Genre::class)->find($id);


        return $this->render('genre.html.twig', [
            'genre' => $genre,
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em)
    {
        $genre= new Genre();

        $form=$this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){
            $genre=$form->getData();
            $em->persist($genre);
            $em->flush();

            $this->addFlash('succes', 'Genre is toegevoegd');
            return $this->redirectToRoute('home');

        }

        return $this->renderForm('add.html.twig', [
            'add' => $form,
        ]);





    }



}