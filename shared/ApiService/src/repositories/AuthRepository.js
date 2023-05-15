export default class AuthRepository {
  constructor(apiClient) {
    this.apiClient = apiClient
    this.apiClient.defaults.baseURL = 'https://api.dayplanner.test'
    this.apiClient.defaults.withCredentials = true
  }

  async login({ email, password }) {
    console.log('logging in')

    const res = await this.apiClient.get('/sanctum/csrf-cookie')
      .then(async (resp) => {
        const response = await this.apiClient.post('/login', {
          email,
          password,
        })

        console.log(response.data)

        return response
        // console.log(user)
        // store user data in pinia
        // store token in session and add it as as authentication to the apiClient
        // this.apiClient.headers.Authentication = `Bearer ${token}`
      })

    // console.log(res)
    // const cookies = res.headers

    // console.log(cookies)
    return res
    // this.apiClient.headers['X-XSRF-Token'] = 'tk'
  }

  logOut() {
    this.apiClient.get()
  }

  register() {
    this.apiClient.post()
  }
}
