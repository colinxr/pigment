export default class AuthRepository {
  constructor(apiClient) {
    this.apiClient = apiClient
    this.apiClient.defaults.baseURL = apiClient.defaults.baseURL.slice(0, -4)
    this.apiClient.defaults.withCredentials = true
  }

  async getAuthenticatedSession() {
    try {
      const res = await this.apiClient.get('/api/user')

      if (res.status !== 200) return false

      return res.data
    } catch (error) {
      console.log(err)
      return false
    }
  }

  async login({ email, password }) {
    const res = this.apiClient.get('api/csrf-cookie').then(async (resp) => {
      const response = await this.apiClient.post('/login', { email, password })
      console.log(response)

      return response
    })

    return res
  }

  logOut() {
    this.apiClient.post('/logout')
  }

  register() {
    this.apiClient.post()
  }
}
