export default class AuthRepository {
  constructor(apiClient) {
    this.apiClient = apiClient
  }

  async getAuthenticatedSession() {
    try {
      const res = await this.apiClient.get('/user')

      if (res.status !== 200) return false

      return res.data
    } catch (err) {
      console.log(err)
      return false
    }
  }

  async login({ email, password }) {
    const res = this.apiClient.get('/csrf-cookie').then(async (resp) => {
      const response = await this.apiClient.post('/login', { email, password }, {
        baseURL: this.apiClient.defaults.baseURL.slice(0, -4),
      })
      return response
    })

    return res
  }

  async logOut() {
    await this.apiClient.post('/logout', {
      baseURL: this.apiClient.defaults.baseURL.slice(0, -4),
    })
  }

  async register() {
    const res = await this.apiClient.post('/register', {
      baseURL: this.apiClient.defaults.baseURL.slice(0, -4),
    })
  }
}
