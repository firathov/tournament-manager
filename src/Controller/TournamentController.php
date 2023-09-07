<?php

namespace App\Controller;

use App\Entity\Tournament;
use App\Form\TournamentForm;
use App\Repository\{TeamRepository, TournamentRepository};
use App\Service\Tournament\TournamentService;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/tournaments')]
class TournamentController extends AbstractController
{
    /**
     * @param TournamentRepository $tournamentRepository
     *
     * @throws Exception
     *
     * @return Response
     */
    #[Route(path: '/', name: 'tournament_index', methods: ['GET'])]
    public function index(TournamentRepository $tournamentRepository): Response
    {
        $tournaments = $tournamentRepository->findAll();

        return $this->render('tournament/index.html.twig', [
            'tournaments' => $tournaments,
        ]);
    }

    /**
     * @param Request              $request
     * @param TournamentRepository $tournamentRepository
     * @param TeamRepository       $teamRepository
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @return Response
     */
    #[Route(path: '/new', name: 'tournament_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TournamentRepository $tournamentRepository, TeamRepository $teamRepository): Response
    {
        $form = $this->createForm(TournamentForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tournament = $form->getData();
            $selectedTeams = $tournament->getTeams();

            if ($selectedTeams->isEmpty()) {
                $selectedTeams = $teamRepository->findAll();
            }

            foreach ($selectedTeams as $team) {
                $tournament->addTeam($team);
            }

            if (!$tournamentRepository->findOneBy(['name' => $tournament->getName()])) {
                $tournamentRepository->insert($tournament);

                return $this->redirectToRoute('tournament_index');
            }
        }

        return $this->render('tournament/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{slug}', name: 'tournament_show', methods: ['GET'])]
    public function show(#[MapEntity(mapping: ['slug' => 'id'])] Tournament $tournament, TournamentService $tournamentService): Response
    {
        $matches = $tournamentService->generateMatches($tournament);

        return $this->render('tournament/show.html.twig', [
            'tournament' => $tournament,
            'matches' => $matches,
        ]);
    }

    /**
     * @param Tournament           $tournament
     * @param TournamentRepository $tournamentRepository
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @return Response
     */
    #[Route(path: '/{id}/delete', name: 'tournament_delete', methods: ['DELETE'])]
    public function delete(
        Tournament $tournament,
        TournamentRepository $tournamentRepository
    ): Response {
        $tournamentRepository->remove($tournament);

        return $this->redirectToRoute('tournament_index');
    }
}
