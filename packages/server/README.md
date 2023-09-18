# WebSocket Demo with Symfony 6 and Queuing System

This is a Proof of Concept (POC) demonstrating how to implement a WebSocket server and a queuing system using Symfony 6.

## 🌟 Features

- **WebSocket Server**: Real-time communication capabilities.
- **Queuing System**: Asynchronous message handling to improve performance.

## 🛠️ Installation & Setup

Follow these steps to get the project up and running:

### 1. Navigate to Server Directory and Build Docker Containers

Navigate to the `packages/server` directory and build the Docker containers.

```bash
cd packages/server
docker-compose up -d
```

### 2. Initialize Database and Run Migrations

Create the PostgreSQL database and run the migrations to set up the required tables.

```bash
docker-compose exec php bin/console doctrine:database:create
docker-compose exec php bin/console doctrine:migrations:migrate
```

### 3. Start the WebSocket Server

Run the WebSocket server for real-time functionality. The `-vv` flag enables verbose output.

```bash
docker-compose exec php bin/console websocket:server:run -vv
```

### 4. Test the WebSocket Server

Execute this command to test the WebSocket server's functionality.

```bash
docker-compose exec php bin/console websocket:server:test
```

## 🚀 Usage

To push a notification to all connected clients or to a single client identified by a specific `resource id`, run:

```bash
docker-compose exec php bin/console websocket:notification:push "Your Toast!" [--resource-id=<RESOURCE_ID>] [--delay=<DELAY_IN_SECONDS>]
```

Replace `<RESOURCE_ID>` with the resource id of the client you wish to target, or omit it to notify all clients.
