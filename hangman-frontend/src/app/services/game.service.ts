// src/app/services/game.service.ts

import { Injectable } from '@angular/core';
import { LoadingService } from './loading.service';
import axios, { AxiosRequestConfig } from 'axios';

@Injectable({
  providedIn: 'root',
})
export class GameService {
  private apiUrl = 'http://localhost:8000/api';

  constructor(private loadingService: LoadingService) {}

  private async request<T>(config: AxiosRequestConfig): Promise<T> {
    this.loadingService.show();
    try {
      const response = await axios(config);
      return response.data;
    } catch (error) {
      console.error(`Error during API request: ${config.url}`, error);
      throw error;
    } finally {
      this.loadingService.hide();
    }
  }

  async startGame() {
    return this.request<{ game_id: number }>({
      method: 'post',
      url: `${this.apiUrl}/games`,
    });
  }

  async guessLetter(gameId: number, letter: string) {
    return this.request<{ gameState: any }>({
      method: 'post',
      url: `${this.apiUrl}/games/${gameId}/guess`,
      data: { letter },
    });
  }

  async getGameState(gameId: number) {
    return this.request<{ gameState: any }>({
      method: 'get',
      url: `${this.apiUrl}/games/${gameId}`,
    });
  }

  async getGamesHistory() {
    return this.request<any[]>({
      method: 'get',
      url: `${this.apiUrl}/games`,
    });
  }

  async getGameDetails(gameId: number) {
    return this.request<{ gameDetails: any }>({
      method: 'get',
      url: `${this.apiUrl}/games/${gameId}/details`,
    });
  }
}
