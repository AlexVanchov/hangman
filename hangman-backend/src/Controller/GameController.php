<?php
// src/Controller/GameController.php

namespace App\Controller;

use App\Service\GameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GameController
 *
 * This controller handles API requests related to the game functionality.
 * It acts as a layer between the HTTP client and the business logic encapsulated in the GameService.
 * The controller ensures that requests are validated and that the appropriate responses are returned
 * based on the outcome of the operations performed by the GameService.
 *
 * @Route("/api")
 */
class GameController extends AbstractController
{
    private GameService $gameService;

    /**
     * GameController constructor.
     *
     * @param GameService $gameService The service used to manage game operations.
     */
    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    /**
     * Starts a new game.
     *
     * @Route("/games", name="start_game", methods={"POST"})
     * @return JsonResponse JSON response containing the new game ID or an error message.
     */
    public function startGame(): JsonResponse
    {
        try {
            $game = $this->gameService->startNewGame();
            return $this->json(['game_id' => $game->getId()], 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Retrieves the history of all games.
     *
     * @Route("/games", name="get_games_history", methods={"GET"})
     * @return JsonResponse JSON response containing the list of game histories.
     */
    public function getGamesHistory(): JsonResponse
    {
        $data = $this->gameService->getGamesHistory();
        return $this->json($data);
    }

    /**
     * Processes a letter guess for a specific game.
     *
     * @Route("/games/{id}/guess", name="guess_letter", methods={"POST"}, requirements={"id"="\d+"})
     * @param int $id The ID of the game.
     * @param Request $request The HTTP request containing the guessed letter.
     * @return JsonResponse JSON response containing the updated game state or an error message.
     */
    public function guessLetter(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $letter = $data['letter'] ?? null;

        if (!$this->isValidLetter($letter)) {
            return $this->json(['error' => 'Invalid letter'], 400);
        }

        try {
            $gameState = $this->gameService->guessLetter($id, strtolower($letter));
            return $this->json($gameState);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Retrieves the current state of a specific game.
     *
     * @Route("/games/{id}", name="get_game_state", methods={"GET"}, requirements={"id"="\d+"})
     * @param int $id The ID of the game.
     * @return JsonResponse JSON response containing the current state of the game or an error message.
     */
    public function getGameState(int $id): JsonResponse
    {
        try {
            $gameState = $this->gameService->getGameState($id);
            return $this->json($gameState);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Retrieves detailed information of a specific game.
     *
     * @Route("/games/{id}/details", name="get_game_details", methods={"GET"}, requirements={"id"="\d+"})
     * @param int $id The ID of the game.
     * @return JsonResponse JSON response containing the detailed information of the game or an error message.
     */
    public function getGameDetails(int $id): JsonResponse
    {
        try {
            $details = $this->gameService->getGameDetails($id);
            return $this->json($details);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Validates if the given input is a valid letter.
     *
     * @param string|null $letter The letter to validate.
     * @return bool True if the letter is valid; false otherwise.
     */
    private function isValidLetter(?string $letter): bool
    {
        return $letter && strlen($letter) === 1 && ctype_alpha($letter);
    }
}
