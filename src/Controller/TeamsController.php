<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamForm;
use App\Repository\TeamRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/teams')]
class TeamsController extends AbstractController
{
    /**
     * @param TeamRepository $teamRepository
     *
     * @throws Exception
     *
     * @return Response
     */
    #[Route(path: '/', name: 'team_index', methods: ['GET', 'POST'])]
    public function index(TeamRepository $teamRepository): Response
    {
        $teams = $teamRepository->findAll();

        return $this->render('teams/index.html.twig', [
            'teams' => $teams,
        ]);
    }

    /**
     * @param Request        $request
     * @param TeamRepository $teamRepository
     *
     * @throws Exception
     *
     * @return Response
     */
    #[Route(path: '/new', name: 'team_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TeamRepository $teamRepository): Response
    {
        $form = $this->createForm(TeamForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Team $team */
            $team = $form->getData();

            if (!$teamRepository->findOneBy(['name' => $team->getName()])) {
                $teamRepository->insert($team);

                return $this->redirectToRoute('team_index');
            }
        }

        return $this->render('teams/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Team           $team
     * @param TeamRepository $teamRepository
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @return Response
     */
    #[Route(path: '/{id}/delete', name: 'team_delete', methods: ['POST', 'DELETE'])]
    public function delete(
        Team $team,
        TeamRepository $teamRepository
    ): Response {
        $teamRepository->remove($team);

        return $this->redirectToRoute('team_index');
    }
}
