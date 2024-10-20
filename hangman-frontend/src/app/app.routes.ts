import { Routes } from '@angular/router';
import { GameComponent } from './components/game/game.component';
import { GameDetailsComponent } from './components/game-details/game-details.component';
import { GamesHistoryComponent } from './components/games-history/games-history.component';

export const routes: Routes = [
  { path: '', component: GameComponent },
  { path: 'game-details', component: GameDetailsComponent },
  { path: 'games-history', component: GamesHistoryComponent },
  { path: 'game/:id/details', component: GameDetailsComponent }, // New route added

];
