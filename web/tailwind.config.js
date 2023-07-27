/** @type {import('tailwindcss').Config} */
module.exports = {
	content: [
		'./components/**/*.{js,vue,ts}',
		'./layouts/**/*.vue',
		'./pages/**/*.vue',
		'./plugins/**/*.{js,ts}',
		'./nuxt.config.{js,ts}',
		'app.vue',
		'formkit.config.js',
	],
	theme: {
		extend: {},
	},
	/* eslint-disable-next-line */
	plugins: [require('@tailwindcss/typography')],
	safelist: [
		'flex',
		'bg-yellow-100',
		'text-yellow-700',
		'bg-red-100',
		'rounded-lg',
		'p-4',
		'mb-4',
		'text-sm',
		'text-red-700',
		'h-5',
		'inline',
		'mr-3',
		'font-medium',
		'bg-green-100',
		'text-green-700',
	],
}
