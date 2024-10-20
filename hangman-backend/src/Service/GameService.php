<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\GameAttempt;
use App\Repository\GameRepository;
use App\Repository\WordRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class GameService
 *
 * This service is responsible for managing the lifecycle of a game, including creating new games,
 * processing guesses, and retrieving game-related data such as game history and game state.
 * It acts as an application service, orchestrating the interactions between the domain logic
 * (managed by GameDomainService) and the persistence layer (repositories).
 */
class GameService
{
    private EntityManagerInterface $entityManager;
    private WordRepository $wordRepository;
    private GameRepository $gameRepository;
    private GameDomainService $gameDomainService;

    /**
     * GameService constructor.
     *
     * @param EntityManagerInterface $entityManager Entity manager for database operations.
     * @param WordRepository $wordRepository Repository for retrieving words.
     * @param GameRepository $gameRepository Repository for retrieving games.
     * @param GameDomainService $gameDomainService Service for handling game-specific business logic.
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        WordRepository $wordRepository,
        GameRepository $gameRepository,
        GameDomainService $gameDomainService
    ) {
        $this->entityManager = $entityManager;
        $this->wordRepository = $wordRepository;
        $this->gameRepository = $gameRepository;
        $this->gameDomainService = $gameDomainService;
    }

    /**
     * Starts a new game by selecting a random word and persisting a new Game entity.
     *
     * @return Game The newly created game entity.
     * @throws \Exception If no words are available in the database.
     */
    public function startNewGame(): Game
    {
        $word = $this->wordRepository->findRandomWord();
        if (!$word) {
            throw new \Exception('No words available');
        }

        $game = (new Game())->setWord($word);
        $this->entityManager->persist($game);
        $this->entityManager->flush();

        return $game;
    }

    /**
     * Processes a guess for a given game and updates the game state.
     *
     * @param int $gameId The ID of the game.
     * @param string $letter The guessed letter.
     * @return array An array representing the updated game state.
     * @throws \Exception If the game is not found, is over, or the letter was already guessed.
     */
    public function guessLetter(int $gameId, string $letter): array
    {
        $game = $this->gameRepository->find($gameId);
        if (!$game) {
            throw new \Exception('Game not found');
        }

        if ($this->gameDomainService->isGameOver($game)) {
            throw new \Exception('Game is over');
        }

        if ($game->hasLetterBeenGuessed($letter)) {
            throw new \Exception('Letter already guessed');
        }

        $attempt = (new GameAttempt())->setGame($game)->setLetter($letter);
        $game->addAttempt($attempt);

        $this->gameDomainService->updateGameStatus($game);

        $this->entityManager->persist($game);
        $this->entityManager->flush();

        return $this->gameDomainService->createGameState($game);
    }

    /**
     * Retrieves the history of all games.
     *
     * @return array An array of game history summaries.
     */
    public function getGamesHistory(): array
    {
        $games = $this->gameRepository->findAll();
        return array_map(fn(Game $game) => $this->gameDomainService->createGameHistorySummary($game), $games);
    }

    /**
     * Retrieves the current state of a game by its ID.
     *
     * @param int $id The ID of the game.
     * @return array An array representing the current state of the game.
     * @throws \Exception If the game is not found.
     */
    public function getGameState(int $id): array
    {
        $game = $this->gameRepository->find($id);
        if (!$game) {
            throw new \Exception('Game not found');
        }

        return $this->gameDomainService->createGameState($game);
    }

    /**
     * Retrieves the detailed information of a game by its ID.
     *
     * @param int $id The ID of the game.
     * @return array An array representing the detailed information of the game.
     * @throws \Exception If the game is not found.
     */
    public function getGameDetails(int $id): array
    {
        $game = $this->gameRepository->find($id);
        if (!$game) {
            throw new \Exception('Game not found');
        }

        return $this->gameDomainService->createGameDetails($game);
    }
}
