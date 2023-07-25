const { $apiService } = useNuxtApp()
const route = useRoute()

export default defineNuxtRouteMiddleware(async to => {
	const userExists = await $apiService.users.exists(route.params.username)

	if (!userExists) {
		setPageLayout('default')
		return navigateTo('/')
	}

	return to
})
