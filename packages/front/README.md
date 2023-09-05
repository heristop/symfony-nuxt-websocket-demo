# Nuxt 3 Toastification WebSocket Demo

This is a Proof of Concept (POC) demo that illustrates how to integrate real-time notifications in a Nuxt.js application using Vue Toastification.

## 🌟 Features

- **Real-time Notifications**: Using `Vue Toastification`.
- **Nuxt.js 3**: Next generation Vue.js framework.

## 📋 Requirements

- Node.js
- PNPM (Package manager)

## 🛠️ Installation & Setup

### 1. Navigate to Front-End Directory

```bash
cd packages/front
```

### 2. Install Dependencies

Use PNPM to install the required packages:

```bash
pnpm install
```

## 🏁 Running the Project

To start the development server, run:

```bash
pnpm run dev
```

## 🎉 Using Notifications

This project employs `vue-toastification` for managing notifications. Initialization is done in `plugins/toast.js`.

### Steps to Use Notifications

1. **Import `useToast`**:

   ```javascript
   import { useToast } from "vue-toastification";
   ```

2. **Initialize in the `setup` function**:

   ```javascript
   const toast = useToast();
   ```

3. **Trigger a Notification**:

   ```javascript
   toast.success("Notification content", {
     timeout: 2000,
   });
   ```

## 📚 Further Reading

- [Nuxt.js Official Documentation](https://nuxtjs.org/docs)
- [Vue Toastification GitHub Repository](https://github.com/Maronato/vue-toastification)
