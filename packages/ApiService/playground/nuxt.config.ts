export default defineNuxtConfig({
  runtimeConfig: {
    public: {
      apiUrl: process.env.NUXT_ENV_API_URL
    }
  },
  
  modules: ['../src/module'],

  apiService: {
    apiUrl: process.env.NUXT_ENV_API_URL
  },
  
  devtools: { enabled: true }
})
