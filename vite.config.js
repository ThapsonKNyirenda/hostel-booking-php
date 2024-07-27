import { defineConfig } from 'vite';

export default defineConfig({
  server: {
    watch: {
      // Watch PHP files for changes
      usePolling: true,
      interval: 1000,
    },
  },
});
