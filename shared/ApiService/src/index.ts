import createApiClient from './createApiClient'
import {
	ApiServiceInterface,
	AppointmentRepositoryI,
	AuthRepositoryI,
	ClientRepositoryI,
	MessageRepositoryI,
	UserRepositoryI,
	SubmissionRepositoryI,
	CalendarRepositoryI,
} from './types'

import {
	AppointmentRepository,
	AuthRepository,
	ClientRepository,
	MessageRepository,
	SubmissionRepository,
	UserRepository,
} from './repositories'
import CalendarRepository from './repositories/CalendarRepository'

class ApiService implements ApiServiceInterface {
	appointments: AppointmentRepositoryI

	auth: AuthRepositoryI

	clients: ClientRepositoryI

	calendars: CalendarRepositoryI

	messages: MessageRepositoryI

	submissions: SubmissionRepositoryI

	users: UserRepositoryI

	constructor() {
		const client = createApiClient('https://api.dayplanner.test/api')

		this.appointments = new AppointmentRepository(client)
		this.auth = new AuthRepository(client)
		this.clients = new ClientRepository(client)
		this.calendars = new CalendarRepository(client)
		this.messages = new MessageRepository(client)
		this.submissions = new SubmissionRepository(client)
		this.users = new UserRepository(client)
	}
}

export default new ApiService()
