module.exports = {
	apps: [
		{
			name: 'PigmentFrontEnd',
			port: '3000',
			exec_mode: 'cluster',
			instances: 'max',
			script: '.output/server/index.mjs',
			env: {
				NUXT_ENV_API_URL: 'https://api.usepigment.com/api',
			},
		},
	],
}
