import useAuthStore from '@/stores/auth'
import ApiService from '@dayplanner/apiservice'

export default defineNuxtRouteMiddleware(async to => {
	const store = useAuthStore()

	console.log(store.user)
	if (!store.user) {
		if (['login', 'register'].includes(to.name)) return

		// setPageLayout('auth')
		return navigateTo('/login')
	}
	console.log(ApiService.axios.defaults)

	ApiService.axios.defaults.headers.Authorization = `Bearer ${store.user.token}`

	console.log(ApiService.axios.defaults)

	setPageLayout('default')
	if (['login', 'register'].includes(to.name)) return navigateTo('/')
})
