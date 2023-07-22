<script setup>
import useAuthStore from '@/stores/auth'

const { $apiService } = useNuxtApp()
const route = useRoute()
const { lastURL } = useAuthStore()

definePageMeta({
	middleware: 'user-is-authenticated',
	layout: 'auth',
})

onMounted(async () => {
	const res = await $apiService.auth.setOAuthToken(route.query.code)

	if (res.data.status === 'error') {
		console.log(res.data)

		return
	} // redirect to auth url

	navigateTo(lastURL)
})
</script>

<template>
	<div class="grid h-screen place-items-center">
		<h2>Redirecting...</h2>
	</div>
</template>
