export default class AuthRepository {
  constructor(apiClient) {
    this.apiClient = apiClient
    this.apiClient.defaults.baseURL = 'https://local.dayplanner.com'
    this.apiClient.defaults.withCredentials = true
  }

  async login({ email, password }) {
    const res = await this.apiClient.get('/sanctum/csrf-cookie')

    const cookies = res.headers['set-cookie']

    console.log(cookies)

    // this.apiClient.headers['X-XSRF-Token'] = 'tk'
    const { data } = await this.apiClient.post('/login', {
      email,
      password,
      _token: document.cookie.split('=')[1],
    })

    const { user, token } = data

    console.log(user)
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
