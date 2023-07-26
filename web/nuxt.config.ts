import path from 'path'

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  alias: {
		'@': process.cwd(),
	},

  devtools: { enabled: true },

  modules: ['@pigment/api-service'],

	apiService: {
		apiUrl: process.env.NUXT_ENV_API_URL!
	},
})


// NUXT_ENV_API_URL=https://api.pigment.biz/api NUXT_NODE_TLS_REJECT_UNAUTHORIZED=0

// NUXT_ENV_API_URL=https://api.pigment.biz/api NUXT_NODE_TLS_REJECT_UNAUTHORIZED=0

    // "dev": "NUXT_ENV_API_URL=https://api.pigment.biz/api NUXT_NODE_TLS_REJECT_UNAUTHORIZED=0 npx nuxi dev --host pigment.biz --https --port 3001 --ssl-cert pigment.biz.pem --ssl-key pigment.biz-key.pem",
