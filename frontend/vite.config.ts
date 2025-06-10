import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vite.dev/config/
export default defineConfig({
  plugins: [react()],
  server: {
    port: 8080,
    host: '45.133.235.23',
    allowedHosts:['crm.vordoom.net','vordoom.net'],// Или конкретный IP
  },
})