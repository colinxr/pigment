import BaseRepository from './BaseRepository'

export default class AuthRepository extends BaseRepository {
  async getCSRFToken() {
    const resp = await this.apiClient.get('/sanctum/csrf-cookie')

    return resp.headers['set-cookie']
  }

  async login({ email, password }) {
    const cookies = await this.getCSRFToken()

    this.apiClient.headers['X-XSRF-Token'] = cookies['X-CSRF-TOKEN']

    const { data } = await this.apiClient.post('/login', { email, password })

    const { user, token } = data
    // store user data in pinia
    // store token in session and add it as as authentication to the apiClient
    this.apiClient.headers.Authentication = `Bearer ${token}`
  }

  logOut() {
    this.apiClient.get()
  }

  register() {
    this.apiClient.post()
  }
}
