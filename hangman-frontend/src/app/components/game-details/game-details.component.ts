// src/app/components/game-details/game-details.component.ts

import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { GameService } from '../../services/game.service';
import { CommonModule } from '@angular/common';
import { GameComponent } from '../game/game.component';

@Component({
  selector: 'app-game-details',
  templateUrl: './game-details.component.html',
  standalone: true,
  imports: [CommonModule, GameComponent],
})
export class GameDetailsComponent implements OnInit {
  gameDetails: any;

  constructor(private route: ActivatedRoute, private gameService: GameService) {}

  ngOnInit(): void {
    const gameId = this.route.snapshot.params['id'];
    if (gameId) {
      this.loadGameDetails(gameId);
    }
  }

  async loadGameDetails(gameId: number) {
    this.gameDetails = await this.gameService.getGameDetails(gameId);
  }
}
