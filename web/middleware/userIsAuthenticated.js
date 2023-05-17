import ApiService from '@dayplanner/ApiService'
import useAuthStore from '@/stores/auth'

export default defineNuxtRouteMiddleware(async (to, from) => {
  const store = useAuthStore()
  console.log(to);

  console.log(store);

  if (!store.user) {
    const authSession = await ApiService.auth.getAuthenticatedSession()
    if (!authSession) {
      if (['login', 'register'].includes(to.name)) return 

      return navigateTo('/login')
    }

    store.login(authSession)
    return navigateTo(to.path)
  }

  // /redirct to index
  // return navigateTo(to)
})