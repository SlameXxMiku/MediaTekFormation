<?php

namespace App\Controller\admin;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminFormationsController extends AbstractController
{
    private FormationRepository $formationRepository;
    private PlaylistRepository $playlistRepository;
    private CategorieRepository $categorieRepository;
    private EntityManagerInterface $om;

    public function __construct(
        FormationRepository $formationRepository,
        PlaylistRepository $playlistRepository,
        CategorieRepository $categorieRepository,
        EntityManagerInterface $om
    ) {
        $this->formationRepository  = $formationRepository;
        $this->playlistRepository   = $playlistRepository;
        $this->categorieRepository  = $categorieRepository;
        $this->om                   = $om;
    }

    #[Route('/admin', name: 'admin.formations')]
    public function index(): Response
    {
        $formations = $this->formationRepository->findAllOrderBy('publishedAt', 'DESC');
        $playlists  = $this->playlistRepository->findAll();
        $categories = $this->categorieRepository->findAll();

        return $this->render('admin/formations.html.twig', [
            'formations' => $formations,
            'playlists'  => $playlists,
            'categories' => $categories,
        ]);
    }

    #[Route('/admin/tri/{champ}/{ordre}', name: 'admin.formations.sort')]
    public function sort(string $champ, string $ordre): Response
    {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre);

        $playlists  = $this->playlistRepository->findAll();
        $categories = $this->categorieRepository->findAll();

        return $this->render('admin/formations.html.twig', [
            'formations' => $formations,
            'playlists'  => $playlists,
            'categories' => $categories,
            'champ'      => $champ,
            'ordre'      => $ordre,
        ]);
    }

    #[Route(
        '/admin/recherche/{champ}/{table}',
        name: 'admin.formations.findallcontain',
        methods: ['POST'],
        defaults: ['table' => '']
    )]
    public function findAllContain(string $champ, Request $request, string $table = ''): Response
    {
        $valeur     = $request->get('recherche');
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $playlists  = $this->playlistRepository->findAll();
        $categories = $this->categorieRepository->findAll();

        return $this->render('admin/formations.html.twig', [
            'formations' => $formations,
            'playlists'  => $playlists,
            'categories' => $categories,
            'valeur'     => $valeur,
            'table'      => $table,
        ]);
    }

    #[Route('/admin/ajout', name: 'admin.formations.ajout')]
    public function ajout(Request $request): Response
    {
        $formation     = new Formation();
        $formFormation = $this->createForm(FormationType::class, $formation);
        $formFormation->handleRequest($request);

        if ($formFormation->isSubmitted() && $formFormation->isValid()) {
            $this->om->persist($formation);
            $this->om->flush();

            return $this->redirectToRoute('admin.formations');
        }

        return $this->render('admin/formations.ajout.html.twig', [
            'formation'     => $formation,
            'formformation' => $formFormation->createView(),
        ]);
    }

    #[Route('/admin/edit/{id}', name: 'admin.formations.edit')]
    public function edit(Formation $formation, Request $request): Response
    {
        $formFormation = $this->createForm(FormationType::class, $formation);
        $formFormation->handleRequest($request);

        if ($formFormation->isSubmitted() && $formFormation->isValid()) {
            $this->om->flush();

            return $this->redirectToRoute('admin.formations');
        }

        return $this->render('admin/formations.edit.html.twig', [
            'formation'     => $formation,
            'formformation' => $formFormation->createView(),
        ]);
    }

    #[Route('/admin/suppr/{id}', name: 'admin.formations.suppr')]
    public function suppr(Formation $formation): Response
    {
        $this->om->remove($formation);
        $this->om->flush();

        return $this->redirectToRoute('admin.formations');
    }
}
