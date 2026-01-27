<?php

namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoriesController extends AbstractController
{
    private CategorieRepository $categorieRepository;
    private EntityManagerInterface $om;

    public function __construct(CategorieRepository $categorieRepository, EntityManagerInterface $om)
    {
        $this->categorieRepository = $categorieRepository;
        $this->om                  = $om;
    }

    #[Route('/admin/categories', name: 'admin.categories')]
    public function index(): Response
    {
        $categories = $this->categorieRepository->findAll();

        return $this->render('admin/categories.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/admin/categories/ajout', name: 'admin.categories.ajout', methods: ['POST'])]
    public function ajout(Request $request): Response
    {
        $nom = trim((string) $request->get('name'));

        if ($nom === '') {
            $this->addFlash('error', 'Le nom de la catégorie ne peut pas être vide.');
            return $this->redirectToRoute('admin.categories');
        }

        $existe = $this->categorieRepository->findOneBy(['name' => $nom]);
        if ($existe) {
            $this->addFlash('error', 'Une catégorie avec ce nom existe déjà.');
            return $this->redirectToRoute('admin.categories');
        }

        $categorie = new Categorie();
        $categorie->setName($nom);

        $this->om->persist($categorie);
        $this->om->flush();

        $this->addFlash('success', 'Catégorie ajoutée.');

        return $this->redirectToRoute('admin.categories');
    }

    #[Route('/admin/categories/suppr/{id}', name: 'admin.categories.suppr')]
    public function suppr(Categorie $categorie): Response
    {
        if (method_exists($categorie, 'getFormations') && $categorie->getFormations()->count() > 0) {
            $this->addFlash('error', 'Impossible de supprimer une catégorie rattachée à des formations.');
            return $this->redirectToRoute('admin.categories');
        }

        $this->om->remove($categorie);
        $this->om->flush();

        $this->addFlash('success', 'Catégorie supprimée.');

        return $this->redirectToRoute('admin.categories');
    }
}
