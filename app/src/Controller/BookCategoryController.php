<?php

namespace App\Controller;

use App\Entity\BookCategory;
use App\Form\BookCategoryType;
use App\Repository\BookCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/category')]
final class BookCategoryController extends AbstractController
{
    #[Route(name: 'app_category_index', methods: ['GET'])]
    public function index(BookCategoryRepository $bookCategoryRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $bookCategoryRepository->createQueryBuilder('bc')->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('category/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $bookCategory = new BookCategory();
        $form = $this->createForm(BookCategoryType::class, $bookCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bookCategory);
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/new.html.twig', [
            'category' => $bookCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_category_show', methods: ['GET'])]
    public function show(BookCategory $bookCategory): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $bookCategory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BookCategory $bookCategory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookCategoryType::class, $bookCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/edit.html.twig', [
            'category' => $bookCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_category_delete', methods: ['POST'])]
    public function delete(Request $request, BookCategory $bookCategory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bookCategory->getId(), $request->getPayload()->getString('_token'))) {
            if($bookCategory->getBooks()->count() > 0) {
                $this->addFlash('danger', 'Category cannot be deleted! Used references with books');

                return $this->redirectToRoute('app_category_show', ['id' => $bookCategory->getId()], Response::HTTP_SEE_OTHER);
            }

            $entityManager->remove($bookCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
