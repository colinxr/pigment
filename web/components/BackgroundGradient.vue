<template>
	<div class="gradient">
		<div class="overlay text-white h-screen">
			<slot></slot>
		</div>
	</div>
</template>

<style>
.gradient {
	background-image: radial-gradient(
			rgba(255, 255, 255, 0.25),
			rgba(255, 255, 255, 0) 40%
		),
		radial-gradient(
			hsla(44, 100%, 66%, 1) 30%,
			hsla(338, 68%, 65%, 1),
			hsla(338, 68%, 65%, 0.4) 41%,
			transparent 52%
		),
		radial-gradient(hsla(272, 100%, 60%, 1) 37%, transparent 46%),
		linear-gradient(155deg, transparent 65%, hsla(142, 70%, 49%, 1) 95%),
		linear-gradient(45deg, hsla(213, 100%, 44%, 1), hsla(209, 100%, 53%, 1));

	background-position: bottom left, 109% 68%, 109% 68%, center, center;
	background-size: 200% 200%, 285% 500%, 285% 500%, cover, cover;
	background-repeat: no-repeat;
}
.overlay {
	background-color: rgba(28, 43, 51, 0.95);
}
</style>
