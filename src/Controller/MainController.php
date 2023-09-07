<?php

namespace App\Controller;

use App\Repository\TournamentRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/')]
class MainController extends AbstractController
{
    /**
     * @param TournamentRepository $tournamentRepository
     *
     * @throws Exception
     *
     * @return Response
     */
    #[Route(path: '/', name: 'main_index', methods: ['GET'])]
    public function index(TournamentRepository $tournamentRepository): Response
    {
        $tournaments = $tournamentRepository->findAll();

        return $this->render('main/index.html.twig', [
            'tournaments' => $tournaments,
        ]);
    }
}
