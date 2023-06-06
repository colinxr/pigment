<script setup>
import ModalWindow from '@/components/Modal/ModalWindow.vue'
</script>

<template>
	<div class="flex align-start w-full">
		<slot />
		<ModalWindow />
	</div>
</template>
