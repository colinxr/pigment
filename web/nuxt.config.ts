import path from 'path'


// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  alias: {
    '@dayplanner': path.resolve(__dirname, '..', 'shared')
  },
  css: [
    '@/assets/css/main.css',
  ],
  postcss: {
    plugins: {
      tailwindcss: {},
      autoprefixer: {},
    },
  },
})

// "NODE_TLS_REJECT_UNAUTHORIZED=0 nuxt dev --host dayplanner.test --https  \
// --ssl-cert ~/.config/valet/CA/LaravelValetCASelfSigned.pem \ 
// --ssl-key ~/.config/valet/CA/LaravelValetCASelfSigned.key",
