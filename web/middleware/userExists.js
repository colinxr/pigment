export default defineNuxtRouteMiddleware(async to => {
	const { $apiService } = useNuxtApp()

	const res = await $apiService.users.exists(to.params.username)

	if (!res) {
		setPageLayout('default')
		return navigateTo('/')
	}

	return
})
