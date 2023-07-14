module.exports = {
	apps: [
		{
			name: 'PigmentFrontEnd',
			port: '3000',
			exec_mode: 'cluster',
			instances: 'max',
			script: '.output/server/index.mjs',
			env: {
				NUXT_PUBLIC_API_URL: process.env.NUXT_PUBLIC_API_URL,
			},
		},
	],
}
