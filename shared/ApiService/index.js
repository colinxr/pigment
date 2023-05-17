import createApiClient from './src/createApiClient'
import { AuthRepository, UserRepository, SubmissionRepository } from './src/repositories'

class ApiService {
  constructor(accessToken = null) {
    const client = createApiClient({
      baseURL: 'https://api.dayplanner.test/api',
      accessToken,
    })

    this.auth = new AuthRepository(client)
    this.users = new UserRepository(client)
    this.submissions = new SubmissionRepository(client)
  }
}

export default new ApiService()
