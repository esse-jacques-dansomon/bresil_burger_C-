<?php

namespace App\Controller\backend;

use App\Entity\Complement;
use App\Form\ComplementType;
use App\Repository\CategoryRepository;
use App\Repository\ComplementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard/complement")
 */
class CrudComplementController extends AbstractController
{
    /**
     * @Route("/", name="crud_complement_index", methods={"GET"})
     */
    public function index(ComplementRepository $complementRepository): Response
    {
        return $this->render('backend/pages/crud_complement/index.html.twig', [
            'complements' => $complementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="crud_complement_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $complement = new Complement();
        $form = $this->createForm(ComplementType::class, $complement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $complement->setSlug($complement->getName())
                    ->setUpdatedAt(new \DateTime());
            $entityManager->persist($complement);
            $entityManager->flush();

            return $this->redirectToRoute('crud_complement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend/pages/crud_complement/new.html.twig', [
            'complement' => $complement,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="crud_complement_show", methods={"GET"})
     */
    public function show(Complement $complement): Response
    {
        return $this->render('backend/pages/crud_complement/show.html.twig', [
            'complement' => $complement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="crud_complement_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Complement $complement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ComplementType::class, $complement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('crud_complement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend/pages/crud_complement/edit.html.twig', [
            'complement' => $complement,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="crud_complement_delete", methods={"POST"})
     */
    public function delete(Request $request, Complement $complement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$complement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($complement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('crud_complement_index', [], Response::HTTP_SEE_OTHER);
    }
}
