import ApiService from '@dayplanner/ApiService'

export default defineNuxtRouteMiddleware(async (to, from) => {
  if (!user) {
    const authSession = await ApiService.auth.getAuthenticatedSession()
    if (!authSession) {
      return navigateTo('/login')
    }

    // useStorage.setUser()
    // let user proceeed to their destination 
    // return to?
  }

  // if to === /login
  // /redirct to index

  return to
})