import path from 'path'

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
	alias: {
		'@': path.resolve(__dirname),
		'@dayplanner': path.resolve(__dirname, '..', 'shared'),
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

// "NODE_TLS_REJECT_UNAUTHORIZED=0 nuxt dev --host dayplanner.test --https  \
// --ssl-cert ~/.config/valet/CA/LaravelValetCASelfSigned.pem \
// --ssl-key ~/.config/valet/CA/LaravelValetCASelfSigned.key",
