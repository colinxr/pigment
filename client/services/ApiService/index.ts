import { useRuntimeConfig } from "#imports";

const config = useRuntimeConfig()


import { AxiosInstance } from 'axios'
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
	CalendarRepository,
	ClientRepository,
	MessageRepository,
	SubmissionRepository,
	UserRepository,
} from './repositories'

class ApiService implements ApiServiceInterface {
	axios: AxiosInstance

	appointments: AppointmentRepositoryI

	auth: AuthRepositoryI

	clients: ClientRepositoryI

	calendars: CalendarRepositoryI

	messages: MessageRepositoryI

	submissions: SubmissionRepositoryI

	users: UserRepositoryI

	constructor() {
		console.log(config.public.api_url);
		
		this.axios = createApiClient(config.public.api_url)

		this.appointments = new AppointmentRepository(this.axios)
		this.auth = new AuthRepository(this.axios)
		this.calendars = new CalendarRepository(this.axios)
		this.clients = new ClientRepository(this.axios)
		this.messages = new MessageRepository(this.axios)
		this.submissions = new SubmissionRepository(this.axios)
		this.users = new UserRepository(this.axios)	
	}
}

export default new ApiService()
