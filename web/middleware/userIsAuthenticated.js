import useAuthStore from '@/stores/auth'
import ApiService from '@dayplanner/apiservice'

export default defineNuxtRouteMiddleware(async to => {
	const store = useAuthStore()

	if (!store.user) {
		if (to.fullPath === '/') return

		setPageLayout('auth')
		if (['login', 'register'].includes(to.name)) return

		return navigateTo('/login')
	}

	ApiService.axios.defaults.headers.Authorization = `Bearer ${store.user.token}`

	setPageLayout('default')
	if (['login', 'register'].includes(to.name)) return navigateTo('/app')
})
