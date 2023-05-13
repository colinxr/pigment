import createApiClient from './src/createApiClient'
import { AuthRepository, UsersRepository } from './src/repositories'

// apiClient.auth = repos.AuthRepository
// apiClient.user = repos.UserRepository

class ApiService {
  constructor(accessToken = null) {
    this.client = createApiClient({
      baseUrl: 'https://local.dayplanner.com/api',
      accessToken,
    })

    this.auth = new AuthRepository(this.client)
    this.users = new UsersRepository(this.client)
  }
}

export default ApiService
