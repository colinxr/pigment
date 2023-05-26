import createApiClient from './createApiClient'
import {
  ApiServiceInterface, 
  AppointmentRepositoryI, 
  AuthRepositoryI, 
  ClientRepositoryI,
  MessageRepositoryI, 
  UserRepositoryI, 
  SubmissionRepositoryI, 
} from './types'

import {
  AppointmentRepository, 
  AuthRepository, 
  ClientRepository,
  MessageRepository, 
  SubmissionRepository,
  UserRepository, 
} from './repositories'

class ApiService implements ApiServiceInterface {
  appointments: AppointmentRepositoryI
  
  auth: AuthRepositoryI

  clients: ClientRepositoryI
  
  messages: MessageRepositoryI
  
  submissions: SubmissionRepositoryI
  
  users: UserRepositoryI

  constructor() {
    const client = createApiClient('https://api.dayplanner.com/api')

    this.appointments = new AppointmentRepository(client)
    this.auth = new AuthRepository(client)
    this.clients = new ClientRepository(client)
    this.messages = new MessageRepository(client)
    this.submissions = new SubmissionRepository(client)
    this.users = new UserRepository(client)
  }
}

export default new ApiService()
