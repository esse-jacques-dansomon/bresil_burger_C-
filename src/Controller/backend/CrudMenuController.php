<?php

namespace App\Controller\backend;

use App\Entity\Menu;
use App\Form\MenuType;
use App\Repository\MenuRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/dashboard/menu")
 */
class CrudMenuController extends AbstractController
{
    /**
     * @Route("/", name="crud_menu_index", methods={"GET"})
     * @param MenuRepository $menuRepository
     * @return Response
     */
    public function index(MenuRepository $menuRepository): Response
    {
        return $this->render('backend/pages/crud_menu/index.html.twig', [
            'menus' => $menuRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="crud_menu_new", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $meucat = $categoryRepository->findByName('menu');
            $menu->setCategory($meucat)
                    ->setSlug($menu->getName())
                    ->setUpdatedAt(new \DateTime());
            $entityManager->persist($menu);
            $entityManager->flush();

            return $this->redirectToRoute('crud_menu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend/pages/crud_menu/new.html.twig', [
            'menu' => $menu,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="crud_menu_show", methods={"GET"})
     */
    public function show(Menu $menu): Response
    {
        return $this->render('backend/pages/crud_menu/show.html.twig', [
            'menu' => $menu,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="crud_menu_edit", methods={"GET", "POST"})
     * @param Request $request
     * @param Menu $menu
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(Request $request, Menu $menu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('crud_menu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend/pages/crud_menu/edit.html.twig', [
            'menu' => $menu,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="crud_menu_delete", methods={"POST"})
     */
    public function delete(Request $request, Menu $menu, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$menu->getId(), $request->request->get('_token'))) {
            $entityManager->remove($menu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('crud_menu_index', [], Response::HTTP_SEE_OTHER);
    }
}
