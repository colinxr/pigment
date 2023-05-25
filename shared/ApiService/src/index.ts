import createApiClient from './createApiClient'
import {
  ApiServiceInterface, SubmissionRepositoryI, MessageRepositoryI, UserRepositoryI, AuthRepositoryI, ClientRepositoryI,
} from './types'

import {
  AuthRepository, UserRepository, SubmissionRepository, MessageRepository, ClientRepository,
} from './repositories'

class ApiService implements ApiServiceInterface {
  auth: AuthRepositoryI

  users: UserRepositoryI

  submissions: SubmissionRepositoryI

  messages: MessageRepositoryI

  clients: ClientRepositoryI

  constructor() {
    const client = createApiClient('https://api.dayplanner.test/api')

    this.auth = new AuthRepository(client)
    this.users = new UserRepository(client)
    this.submissions = new SubmissionRepository(client)
    this.messages = new MessageRepository(client)
    this.clients = new ClientRepository(client)
  }
}

export default new ApiService()
