// src/app/components/games-history/games-history.component.ts

import { Component, OnInit } from '@angular/core';
import { GameService } from '../../services/game.service';
import { Router } from '@angular/router';

import { CommonModule } from '@angular/common'; // Provides *ngFor and *ngIf
import { RouterModule } from '@angular/router';

@Component({
  selector: 'app-games-history',
  templateUrl: './games-history.component.html',
  styleUrls: ['./games-history.component.scss'],
  standalone: true, // Marks this component as standalone
  imports: [CommonModule, RouterModule], // Imports necessary Angular modules
})
export class GamesHistoryComponent implements OnInit {
  gamesHistory: any[] = [];

  constructor(private gameService: GameService, private router: Router) {}

  ngOnInit(): void {
    this.loadGamesHistory();
  }

  async loadGamesHistory() {
    this.gamesHistory = await this.gameService.getGamesHistory();
    
  }

  viewGameDetails(gameId: number) {
    this.router.navigate(['/games', gameId, 'details']);
  }
  
  /**
   * Formats the selected letters for display.
   * Ensures that selected_letters is an array before joining.
   */
  formatSelectedLetters(selectedLetters: string[] | null): string {
    if (Array.isArray(selectedLetters)) {
      return selectedLetters.join(', ').toUpperCase();
    }
    return 'N/A';
  }
}
