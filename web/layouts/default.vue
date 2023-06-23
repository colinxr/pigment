<script setup>
import AppSidebar from '@/components/PrimeVue/AppSidebar.vue'
import ModalWindow from '@/components/Modal/ModalWindow.vue'

onMounted(() => {
	console.log('wtf')
})
</script>

<template>
	<div class="dashboard h-screen overflow-hidden flex align-start">
		<AppSidebar />
		<div class="overflow-scroll w-full">
			<slot />
		</div>
		<ModalWindow />
	</div>
</template>
