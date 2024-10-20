<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\GameAttempt;

/**
 * Class GameDomainService
 *
 * This class is responsible for handling the core business logic related to the Game entity.
 * It performs domain-specific operations, such as updating game status, checking if the game is over,
 * and generating representations of game states and histories.
 * 
 * This class acts as a Domain Service because it contains logic that operates on one or more domain entities 
 * (like Game and GameAttempt) but doesn't belong directly to any single entity. The service encapsulates
 * and centralizes game rules and calculations, ensuring they are consistent and reusable.
 */
class GameDomainService
{
    /**
     * Updates the game status based on the current state of the game.
     *
     * @param Game $game The game entity to update.
     */
    public function updateGameStatus(Game $game): void
    {
        // If the game is already over, no further updates are needed.
        if ($this->isGameOver($game)) {
            return;
        }

        // Get the letters of the word and the guessed letters so far.
        $wordLetters = str_split(strtolower($game->getWord()->getWord()));
        $guessedLetters = $game->getGuessedLetters();
        $remainingLetters = array_diff($wordLetters, $guessedLetters);

        // If there are no remaining letters to guess, the player has won.
        if (empty($remainingLetters)) {
            $game->setWinStatus(true);
        } 
        // If the number of incorrect attempts reaches the limit, the player has lost.
        elseif ($this->getIncorrectAttemptsCount($game) >= Game::MAX_INCORRECT_ATTEMPTS) {
            $game->setWinStatus(false);
        }
    }

    /**
     * Checks if the game is over.
     *
     * @param Game $game The game entity to check.
     * @return bool True if the game is over, false otherwise.
     */
    public function isGameOver(Game $game): bool
    {
        // The game is over if the win status is either true or false.
        return $game->getWinStatus() !== null;
    }

    /**
     * Calculates the number of incorrect attempts made in the game.
     *
     * @param Game $game The game entity to calculate the incorrect attempts for.
     * @return int The number of incorrect attempts.
     */
    public function getIncorrectAttemptsCount(Game $game): int
    {
        $wordLetters = str_split(strtolower($game->getWord()->getWord()));
        $incorrectGuesses = array_diff($game->getGuessedLetters(), $wordLetters);

        return count($incorrectGuesses);
    }

    /**
     * Creates a representation of the current game state.
     *
     * @param Game $game The game entity for which the state is generated.
     * @return array An associative array representing the current state of the game.
     */
    public function createGameState(Game $game): array
    {
        return [
            'game_id' => $game->getId(),
            'word' => $this->getWordDisplay($game),
            'guessed_letters' => $game->getGuessedLetters(),
            'incorrect_attempts' => $this->getIncorrectAttemptsCount($game),
            'max_incorrect_attempts' => Game::MAX_INCORRECT_ATTEMPTS,
            'win' => $game->getWinStatus(),
            'is_over' => $this->isGameOver($game),
        ];
    }

    /**
     * Creates a summary representation of the game's history.
     *
     * @param Game $game The game entity for which the history summary is generated.
     * @return array An associative array summarizing the game's history.
     */
    public function createGameHistorySummary(Game $game): array
    {
        return [
            'id' => $game->getId(),
            'word' => $game->getWord()->getWord(),
            'selected_letters' => $game->getGuessedLetters(),
            'win' => $game->getWinStatus() === null ? 'not completed' : ($game->getWinStatus() ? 'yes' : 'no'),
            'datetime' => $game->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Creates a detailed representation of the game's history, including each guess.
     *
     * @param Game $game The game entity for which the detailed history is generated.
     * @return array An associative array containing detailed information about the game's history.
     */
    public function createGameDetails(Game $game): array
    {
        return [
            'id' => $game->getId(),
            'word' => $game->getWord()->getWord(),
            'selected_letters' => $game->getGuessedLetters(),
            'incorrect_attempts' => $this->getIncorrectAttemptsCount($game),
            'max_incorrect_attempts' => Game::MAX_INCORRECT_ATTEMPTS,
            'win' => $game->getWinStatus() === null ? 'not completed' : ($game->getWinStatus() ? 'yes' : 'no'),
            'datetime' => $game->getCreatedAt()->format('Y-m-d H:i:s'),
            'attempts' => array_map(function (GameAttempt $attempt) {
                return [
                    'letter' => $attempt->getLetter(),
                    'datetime' => $attempt->getCreatedAt()->format('Y-m-d H:i:s'),
                ];
            }, $game->getAttempts()),
        ];
    }

    /**
     * Generates a display version of the word with guessed letters revealed and others as underscores.
     *
     * @param Game $game The game entity for which the word display is generated.
     * @return string The display version of the word.
     */
    private function getWordDisplay(Game $game): string
    {
        $word = $game->getWord()->getWord();
        if ($this->isGameOver($game)) {
            return $word;
        }
        $wordLetters = str_split($word);
        $guessedLetters = $game->getGuessedLetters();

        $displayWord = array_map(
            function ($letter) use ($guessedLetters) {
                return in_array($letter, $guessedLetters, true) ? $letter : '_';
            },
            $wordLetters
        );

        return implode(' ', $displayWord);
    }
}
