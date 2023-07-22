import dotenv from 'dotenv';
dotenv.config({ path: '../.env' });


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

const apiUrl = process.env.API_URL!

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
		console.log(apiUrl);
		
		this.axios = createApiClient(apiUrl)

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
