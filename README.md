# Hangman Project

## Init the Project (Docker)

To set up the entire project, run the following command:

```bash
docker-compose up --build
```

This will build and run the necessary Docker containers for the frontend, backend, and database.

## Frontend (Angular)

### Components

- **GameComponent**: Handles the gameplay logic and interface for the active Hangman game.
- **GamesHistoryComponent**: Displays a list of all previously played games.
- **GameDetailsComponent**: Shows detailed information about a selected past game.

### Services

- **GameService**: Manages API calls to interact with the backend, handling actions such as starting a game, guessing letters, and fetching game history.
- **LoadingService**: Manages loading states for asynchronous operations.

## Backend (Symfony)

### API Endpoints

- **`/api/games`**: Starts a new game.
- **`/api/games/{id}/guess`**: Submits a guessed letter for the specified game.
- **`/api/games/{id}`**: Fetches the current state of a game.
- **`/api/games`**: Fetches a list of previously played games.
- **`/api/games/{id}/details`**: Fetches detailed information about a specific game.

## Prerequisites

Ensure you have the following installed:

- Docker
- Node.js and npm (for frontend development outside Docker)
- Angular CLI
- PHP and Composer (for backend development outside Docker)

## Setup Instructions

### 1. Clone the Repository

```bash
git clone <repository-url>
```

### 2. Build and Run the Application Using Docker

Navigate to the root directory and run:

```bash
docker-compose up --build
```

This will set up the frontend, backend, and database services.

### 3. Access the Application

- **Frontend**: [http://localhost:4200](http://localhost:4200)
- **Backend API**: [http://localhost:8000](http://localhost:8000)
- **phpMyAdmin (optional)**: [http://localhost:8081](http://localhost:8081)

## Development

### Frontend

To run the frontend application locally (outside Docker):

1. Navigate to the `hangman-frontend` directory:
   
   ```bash
   cd hangman-frontend
   ```

2. Install dependencies:
   
   ```bash
   npm install
   ```

3. Start the development server:
   
   ```bash
   ng serve
   ```

Access the app at [http://localhost:4200](http://localhost:4200).

### Backend

To run the backend application locally (outside Docker):

1. Navigate to the `hangman-backend` directory:
   
   ```bash
   cd hangman-backend
   ```

2. Install dependencies:
   
   ```bash
   composer install
   ```

3. Run the Symfony server:
   
   ```bash
   symfony serve
   ```

## Usage

### Gameplay

- The user starts a game and guesses letters one by one.
- If the guessed letter is correct, it is revealed in the word.
- If the guessed letter is incorrect, a new part of the hangman is drawn.
- The game ends when the user either completes the word or reaches the maximum allowed incorrect guesses.

### Viewing Game History

- The user can view a list of all games they have played.
- Selecting a game from the history shows detailed information, including guessed letters and whether the game was won.

## Known Issues

- If using Docker for development, make sure file permissions are correctly set to avoid permission errors.
- Without rows in "words" table

## Technologies Used

- Backend - PHP, API layer, use Symfony framework
- DB - MySQL
- Front-end - Angular

