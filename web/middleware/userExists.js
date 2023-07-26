export default defineNuxtRouteMiddleware(async to => {
	const route = useRoute()
	const { $apiService } = useNuxtApp()

	const res = await $apiService.users.exists(route.params.username)

	if (!res) {
		setPageLayout('default')
		return navigateTo('/')
	}

	return
})
