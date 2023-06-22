import { defineStore } from 'pinia'
import { AuthUserI } from './types'

const useAuthStore = defineStore('authStore', {
	state: () => {
		return {
			user: undefined as AuthUserI | undefined,
			lastURL: '',
		}
	},
	persist: {
    storage: persistedState.cookiesWithOptions({
      sameSite: 'strict',
    }),
  },
	actions: {
		setUser(authUser: AuthUserI) {
			console.log(authUser)

			this.user = authUser
		},

		removeUser() {
			this.user = undefined
		},

		setLastURL(url = '') {
			this.lastURL = url
		},
	},
})

export default useAuthStore
