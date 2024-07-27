import { defineConfig } from 'vite'
import path from 'path'

export default defineConfig({
  build: {
    outDir: 'public/assets',
    assetsDir: '',
    sourcemap: false,
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'src/main.css')
      },
      output: {
        assetFileNames: 'main.css', // Use the original filename
      }
    },
    assetFileNames: 'main.css' // Output filename without hash
  }
})