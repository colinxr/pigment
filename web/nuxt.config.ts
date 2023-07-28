import path from 'path'

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
	alias: {
		'@': path.resolve(__dirname),
	},
	

	apiService: {
		apiUrl: process.env.NUXT_ENV_API_URL!
	},

	css: [
		'@/assets/styles/main.scss',
	],

  devtools: { enabled: true },

  modules: ['@nuxtjs/tailwindcss', 'api-service', 'form-helpers'],

	postcss: {
		plugins: {
			tailwindcss: {},
			autoprefixer: {},
		},
	},

})