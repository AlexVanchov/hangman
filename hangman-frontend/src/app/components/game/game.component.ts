import { Component, OnInit, Input } from '@angular/core';
import { GameService } from '../../services/game.service';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';

@Component({
  selector: 'app-game',
  templateUrl: './game.component.html',
  styleUrls: ['./game.component.scss'],
  standalone: true,
  imports: [FormsModule, CommonModule, RouterModule],
})
export class GameComponent implements OnInit {
    @Input() gameState: any = {
        word: '',
        guessed_letters: [],
        incorrect_attempts: 0,
        is_over: false,
        win: null,
    };    
    @Input() playMode: boolean = true; // Default to play mode
    inputLetter: string = '';
    errorMessage: string = '';

    constructor(private gameService: GameService) {}

    ngOnInit(): void {
        if (this.playMode) {
          this.startNewGame();
        }
      }

    // Getter to format the word
    get formattedWord(): string {
        return this.gameState.word.replace(/ /g, '');
    }

    async startNewGame() {
        const data = await this.gameService.startGame();
        this.loadGameState(data.game_id);
    }

    async loadGameState(gameId: number) {
        this.gameState = await this.gameService.getGameState(gameId);
    }

    async guessLetter() {
        this.errorMessage = '';
        const letter = this.inputLetter.toLowerCase();

        // Front-end validation
        if (!letter || letter.length !== 1 || !/^[a-zA-Z]+$/.test(letter)) {
        this.errorMessage = 'Please enter a valid single letter.';
        return;
        }

        if (this.gameState.guessed_letters.includes(letter)) {
        this.errorMessage = 'You have already guessed this letter.';
        return;
        }

        this.gameState = await this.gameService.guessLetter(this.gameState.game_id, letter);
        this.inputLetter = '';
    }
}
