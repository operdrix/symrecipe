<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController
{
    /**
     * Cette route permet d'afficher la liste des ingrédients
     * 
     * @param IngredientRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'ingredient.index', methods: ['GET'])]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $ingredients = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    /**
     * Cette route permet d'afficher le formulaire de création d'un ingrédient et de le traiter
     * 
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ingredient/nouveau', name: 'ingredient.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();

            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash('success', 'Votre ingrédient a bien été créé !');

            return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('pages/ingredient/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Cette route permet d'afficher le formulaire d'édition d'un ingrédient et de le traiter
     * 
     * @param Ingredient $ingredient
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ingredient/edition/{id}', name: 'ingredient.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $manager, Ingredient $ingredient): Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();

            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash('success', 'Votre ingrédient a bien été édité !');

            return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('pages/ingredient/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Cette route permet de supprimer un ingrédient
     * 
     * @param Ingredient $ingredient
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ingredient/suppression/{id}', name: 'ingredient.delete', methods: ['GET'])]
    public function delete(Ingredient $ingredient, EntityManagerInterface $manager): Response
    {

        $manager->remove($ingredient);
        $manager->flush();

        $this->addFlash('success', 'Votre ingrédient a bien été supprimé !');

        return $this->redirectToRoute('ingredient.index');
    }
}
