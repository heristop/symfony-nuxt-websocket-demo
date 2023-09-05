<script setup>
import { onBeforeMount } from 'vue';
import { useToast } from 'vue-toastification';

// Get toast interface
const toast = useToast();

// WebSocket Connection and events
onBeforeMount(() => {
  const envWebSocketHost = import.meta.env.VITE_WEBSOCKET_URL;

  const conn = new WebSocket(envWebSocketHost);

  conn.onmessage = (event) => {
    const data = JSON.parse(event.data);

    if ('event-notification' === data.type) {
      toast(data.message);
    }

    if ('connection-callback' === data.type) {
			localStorage.setItem('resource-id-callback', data.resourceId);

      toast.success(`Connection callback! #${localStorage.getItem('resource-id-callback')}`, {
        timeout: 2000,
      });
    }
  };
});
</script>

<template>
  <div class="readme-container">
    <h1>WebSocket and Queuing System Demo with Symfony 6 / Nuxt 3</h1>

    <h2>🌟 Features</h2>
    <ul>
      <li><strong>WebSocket Server:</strong> Enables real-time messaging and notifications.</li>
      <li><strong>Queuing System:</strong> Facilitates asynchronous message handling for improved performance.</li>
    </ul>

    <h2>🛠️ Quick Setup</h2>

    <h3>1. Build Docker Containers</h3>
    <pre class="code-section">
      docker-compose up -d
    </pre>

    <h3>2. Create Database and Run Migrations</h3>
    <pre class="code-section">
      docker-compose exec php bin/console doctrine:database:create
      docker-compose exec php bin/console doctrine:migrations:migrate
    </pre>

    <h3>3. Run WebSocket Server</h3>
    <pre class="code-section">
      docker-compose exec php bin/console websocket:server:run -vv
    </pre>

    <h2>📨 Usage</h2>
    <p>To push a notification to all clients:</p>
    <pre class="code-section">
      docker-compose exec php bin/console websocket:notification:push "Your Toast!"
    </pre>
  </div>
</template>

<style scoped>
  .readme-container {
    padding: 10px 10%;
    font-family: 'Roboto', sans-serif;
    font-size: 0.9em;
  }

  .code-section {
    background-color: #f2f2f2;
    padding: 10px;
    border-radius: 5px;
    font-family: monospace;
    overflow-x: auto;
  }
</style>
