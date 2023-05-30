import useAuthStore from '@/stores/auth'

export default defineNuxtRouteMiddleware((to) => {
  if (process.server) return

  const store = useAuthStore()

  if (!store.user) {
    const token = window.localStorage.getItem('authToken')

    if (!token) {
      if (['login', 'register'].includes(to.name)) return

      // return redirect('/login')
      navigateTo('/login')
    }

    navigateTo(to)
  }

  if (['login', 'register'].includes(to.name)) navigateTo('/')
})
