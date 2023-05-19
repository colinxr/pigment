import createApiClient from './createApiClient'
import {
  ApiServiceInterface, SubmissionRepositoryI, MessageRepositoryI, UserRepositoryI, AuthRepositoryI,
} from './types'

import {
  AuthRepository, UserRepository, SubmissionRepository, MessageRepository,
} from './repositories'

class ApiService implements ApiServiceInterface {
  auth: AuthRepositoryI

  users: UserRepositoryI

  submissions: SubmissionRepositoryI

  messages: MessageRepositoryI

  constructor() {
    const client = createApiClient('https://api.dayplanner.test/api')

    this.auth = new AuthRepository(client)
    this.users = new UserRepository(client)
    this.submissions = new SubmissionRepository(client)
    this.messages = new MessageRepository(client)
  }
}

export default new ApiService()
