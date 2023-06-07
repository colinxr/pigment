<script setup>
import AppSidebar from '@/components/PrimeVue/AppSidebar.vue'
import ModalWindow from '@/components/Modal/ModalWindow.vue'
</script>

<template>
	<div class="dashboard h-screen overflow-hidden flex align-start">
		<AppSidebar />
		<slot />
		<ModalWindow />
	</div>
</template>
