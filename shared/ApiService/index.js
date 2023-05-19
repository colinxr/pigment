import createApiClient from './src/createApiClient'
import {
  AuthRepository, UserRepository, SubmissionRepository, MessageRepository,
} from './src/repositories'

class ApiService {
  constructor(accessToken = null) {
    const client = createApiClient({
      baseURL: 'https://api.dayplanner.test/api',
      accessToken,
    })

    this.auth = new AuthRepository(client)
    this.users = new UserRepository(client)
    this.submissions = new SubmissionRepository(client)
    this.messages = new MessageRepository(client)
  }
}

export default new ApiService()
