import createApiClient from './src/createApiClient'
import { AuthRepository, UsersRepository } from './src/repositories'

// apiClient.auth = repos.AuthRepository
// apiClient.user = repos.UserRepository

class ApiService {
  constructor(accessToken = null) {
    const client = createApiClient({
      baseURL: 'https://local.dayplanner.com/api',
      accessToken,
    })

    this.auth = new AuthRepository(client)
    this.users = new UsersRepository(client)
  }
}

export default ApiService
