<?php

namespace App\Service\Tournament;

use App\Entity\Tournament;

class TournamentService
{
    public function generateMatches(Tournament $tournament): array
    {
        $teams = $tournament->getTeams()->toArray();
        $matches = [];

        $numTeams = count($teams);
        $maxMatchesPerDay = 4;

        for ($day = 1; $day < $numTeams; $day++) {
            $matchesOfDay = [];

            for ($i = 0; $i < $numTeams / 2; $i++) {
                $team1 = $teams[$i];
                $team2 = $teams[$numTeams - 1 - $i];

                $matchesOfDay[] = [
                    'team1' => $team1->getName(),
                    'team2' => $team2->getName(),
                ];
            }

            $matchesOfDay = array_slice($matchesOfDay, 0, $maxMatchesPerDay);

            $matches[$day] = $matchesOfDay;

            // Перемещаем первую команду в конец массива
            $firstTeam = array_shift($teams);
            $teams[] = $firstTeam;
        }

        return $matches;
    }
}