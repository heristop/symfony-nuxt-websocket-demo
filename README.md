# WebSocket and Queuing System Demo with Symfony 6 and Nuxt.js

This project serves as a Proof of Concept (POC) for implementing a WebSocket server combined with a queuing system. It is built using Symfony 6 and Nuxt.js and features real-time notifications via Vue Toastification.

## 🌟 Features

- **WebSocket Server**: Enables real-time messaging and notifications.
- **Queuing System**: Facilitates asynchronous message handling for improved performance.

## 🛠️ Architecture

### Overview

The project showcases a WebSocket-based architecture where messages are broadcasted in real-time to all connected clients.

### Components

- **Symfony 6**: Hosts the backend and the WebSocket server, built on Ratchet.
- **PostgreSQL**: Houses the `websocket_messages` table where broadcast messages are stored.
- **WebSocket Server (Ratchet Daemon)**: Watches for new messages in the `websocket_messages` table and broadcasts them.
- **Client**: Web apps or services that connect to the WebSocket Server.
- **Resource ID**: A unique identifier for each connected client.
- **Local Storage**: Used for storing the `resourceId` on the client-side.

### Workflow

1. Client establishes a connection to the WebSocket Server.
2. Server assigns a unique `resourceId` to the client.
3. The client stores this `resourceId` in `localStorage`.
4. New messages are added to the `websocket_messages` table.
5. The WebSocket Server broadcasts these new messages to all connected clients.

![Notification System Workflow](/assets/websockets_architecture-Notification_System.png)

## 📋 Requirements

- Docker
- Node.js
- PNPM

## 💻 Installation

Clone the repository and follow the respective README files for setting up both server and client:

```bash
git clone https://github.com/heristop/symfony-nuxt-websocket-demo.git
```

Server: [Installation Guide](/packages/server/README.md#installation) (Symfony 6, PostgreSQL, Ratchet)

Front: [Installation Guide](/packages/front/README.md#installation) (Nuxt 3, Vue Toastification)

## 📝 License

MIT
