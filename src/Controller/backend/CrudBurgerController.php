<?php

namespace App\Controller\backend;

use App\Entity\Burger;
use App\Form\BurgerType;
use App\Repository\BurgerRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashborad/burger")
 */
class CrudBurgerController extends AbstractController
{
    /**
     * @Route("", name="crud_burger_index", methods={"GET"})
     * @param BurgerRepository $burgerRepository
     * @return Response
     */
    public function index(BurgerRepository $burgerRepository): Response
    {
        return $this->render('backend/pages/crud_burger/index.html.twig', [
            'burgers' => $burgerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="crud_burger_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        $burger = new Burger();
        $form = $this->createForm(BurgerType::class, $burger);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $burgercat = $categoryRepository->findByName('burger');
            $burger->setCategory($burgercat)
                    ->setSlug($burger->getName())
                    ->setUpdatedAt(new \DateTime());
            $entityManager->persist($burger);
            $entityManager->flush();

            return $this->redirectToRoute('crud_burger_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend/pages/crud_burger/new.html.twig', [
            'burger' => $burger,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="crud_burger_show", methods={"GET"})
     */
    public function show(Burger $burger): Response
    {
        return $this->render('backend/pages/crud_burger/show.html.twig', [
            'burger' => $burger,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="crud_burger_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Burger $burger, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BurgerType::class, $burger);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('crud_burger_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend/pages/crud_burger/edit.html.twig', [
            'burger' => $burger,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="crud_burger_delete", methods={"POST"})
     */
    public function delete(Request $request, Burger $burger, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$burger->getId(), $request->request->get('_token'))) {
            $entityManager->remove($burger);
            $entityManager->flush();
        }

        return $this->redirectToRoute('crud_burger_index', [], Response::HTTP_SEE_OTHER);
    }
}
