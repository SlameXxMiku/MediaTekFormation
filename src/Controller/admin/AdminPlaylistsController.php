<?php

namespace App\Controller\admin;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\PlaylistRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPlaylistsController extends AbstractController
{
    private PlaylistRepository $playlistRepository;
    private CategorieRepository $categorieRepository;
    private EntityManagerInterface $om;

    public function __construct(
        PlaylistRepository $playlistRepository,
        CategorieRepository $categorieRepository,
        EntityManagerInterface $om
    ) {
        $this->playlistRepository   = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->om                  = $om;
    }

    #[Route('/admin/playlists', name: 'admin.playlists')]
    public function index(): Response
    {
        $playlists  = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();

        return $this->render('admin/playlists.html.twig', [
            'playlists'  => $playlists,
            'categories' => $categories,
        ]);
    }

    #[Route('/admin/playlists/tri/{champ}/{ordre}', name: 'admin.playlists.sort')]
    public function sort(string $champ, string $ordre): Response
    {
        switch ($champ) {
            case 'name':
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case 'nbformations':
                $playlists = $this->playlistRepository->findAllOrderByNbFormations($ordre);
                break;
            default:
                $playlists = $this->playlistRepository->findAllOrderByName('ASC');
                break;
        }

        $categories = $this->categorieRepository->findAll();

        return $this->render('admin/playlists.html.twig', [
            'playlists'  => $playlists,
            'categories' => $categories,
            'champ'      => $champ,
            'ordre'      => $ordre,
        ]);
    }

    #[Route(
        '/admin/playlists/recherche/{champ}/{table}',
        name: 'admin.playlists.findallcontain',
        methods: ['POST'],
        defaults: ['table' => '']
    )]
    public function findAllContain(string $champ, Request $request, string $table = ''): Response
    {
        $valeur     = $request->get('recherche');
        $playlists  = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();

        return $this->render('admin/playlists.html.twig', [
            'playlists'  => $playlists,
            'categories' => $categories,
            'valeur'     => $valeur,
            'table'      => $table,
        ]);
    }

    #[Route('/admin/playlists/ajout', name: 'admin.playlists.ajout')]
    public function ajout(Request $request): Response
    {
        $playlist = new Playlist();
        $form     = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->om->persist($playlist);
            $this->om->flush();

            $this->addFlash('success', 'Playlist ajoutée.');
            return $this->redirectToRoute('admin.playlists');
        }

        return $this->render('admin/playlists.ajout.html.twig', [
            'playlist' => $playlist,
            'form'     => $form->createView(),
        ]);
    }

    #[Route('/admin/playlists/edit/{id}', name: 'admin.playlists.edit')]
    public function edit(Playlist $playlist, Request $request): Response
    {
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->om->flush();

            $this->addFlash('success', 'Playlist modifiée.');
            return $this->redirectToRoute('admin.playlists');
        }

        return $this->render('admin/playlists.edit.html.twig', [
            'playlist' => $playlist,
            'form'     => $form->createView(),
        ]);
    }

    #[Route('/admin/playlists/suppr/{id}', name: 'admin.playlists.suppr')]
    public function suppr(Playlist $playlist): Response
    {
        if ($playlist->getFormations()->count() > 0) {
            $this->addFlash('error', 'Impossible de supprimer une playlist qui contient encore des formations.');
            return $this->redirectToRoute('admin.playlists');
        }

        $this->om->remove($playlist);
        $this->om->flush();

        $this->addFlash('success', 'Playlist supprimée.');
        return $this->redirectToRoute('admin.playlists');
    }
}
