import path from 'path'

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
    // The private keys which are only available server-side
    apiSecret: '123',
    // Keys within public are also exposed client-side
    public: {
			api_url: process.env.API_URL
    }
  },
	modules: ['@pinia/nuxt', '@formkit/nuxt', '@pinia-plugin-persistedstate/nuxt'],
	piniaPersistedstate: {
    cookieOptions: {
      sameSite: 'strict',
    },
		debug: true,
    storage: 'cookies'
  },
	postcss: {
		plugins: {
			tailwindcss: {},
			autoprefixer: {},
		},
	},
	ssr: false,
})