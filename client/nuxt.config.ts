import path from 'path'

console.log(process.env.NUXT_ENV_API_URL);

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
	alias: {
		'@': path.resolve(__dirname),
	},
	
	build: {
		transpile: ['primevue'],
	},

	css: [
		'@/assets/styles/main.scss',
		'@/assets/styles/primevue/theme/theme.css',
		'primeicons/primeicons.css',
		'primevue/resources/primevue.min.css',
	],
	
	runtimeConfig: {
    public: {
			api_url: process.env.NUXT_ENV_API_URL
    }
  },
	
	modules: ['@pinia/nuxt', '@formkit/nuxt', '@pinia-plugin-persistedstate/nuxt', 'api-service', 'form-helpers'],
	
	piniaPersistedstate: {
    cookieOptions: {
      sameSite: 'strict',
    },
		debug: true,
    storage: 'cookies'
  },

	apiService: {
		apiUrl: process.env.NUXT_ENV_API_URL!
	},

	postcss: {
		plugins: {
			tailwindcss: {},
			autoprefixer: {},
		},
	},

	ssr: false,
})