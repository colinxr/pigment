import ApiService from '@dayplanner/apiservice'
import useAuthStore from '@/stores/auth'

export default defineNuxtRouteMiddleware(async (to, from) => {
  const store = useAuthStore()

  if (!store.user) {
    const authSession = await ApiService.auth.getAuthenticatedSession()
    if (!authSession) {
      if (['login', 'register'].includes(to.name)) return 

      return navigateTo('/login')
    }

    store.login(authSession)
    return navigateTo(to.path)
  }

  if (['login', 'register'].includes(to.name)) return navigateTo('/')
})