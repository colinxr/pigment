import useAuthStore from '@/stores/auth'
const { $apiService } = useNuxtApp()

export default defineNuxtRouteMiddleware(async to => {
	const store = useAuthStore()

	if (!store.user) {
		if (to.fullPath === '/') return

		setPageLayout('auth')
		if (['login', 'register'].includes(to.name)) return

		return navigateTo('/login')
	}

	$apiService.axios.defaults.headers.Authorization = `Bearer ${store.user.token}`

	setPageLayout('default')
	if (['login', 'register'].includes(to.name)) return navigateTo('/')
})
