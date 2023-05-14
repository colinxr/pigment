import createApiClient from './src/createApiClient'
import { AuthRepository, UsersRepository } from './src/repositories'

class ApiService {
  constructor(accessToken = null) {
    const client = createApiClient({
      baseURL: 'https://127.0.0.1:5173/',
      accessToken,
    })

    this.auth = new AuthRepository(client)
    this.users = new UsersRepository(client)
  }
}

export default ApiService
