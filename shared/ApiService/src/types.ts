import { AxiosInstance, AxiosResponse } from 'axios'

export interface RegisterFormDataI {
	first_name: string,
	last_name: string,
	username: string,
	email: string,
	password: string,
	password_confirm: string,
}

interface ScheduleDayI {
	open: string,
	close: string,
}
export interface ScheduleDataI {
	sunday: ScheduleDayI|null,
	monday: ScheduleDayI|null,
	tuesday: ScheduleDayI|null,
	wednesday: ScheduleDayI|null,
	thursday: ScheduleDayI|null,
	friday: ScheduleDayI|null,
	saturday: ScheduleDayI|null,
}

export interface AuthRepositoryI {
	apiClient: AxiosInstance
	getAuthenticatedSession(): Promise<AxiosResponse>
	/* eslint-disable-next-line */
  login(data: object): Promise<AxiosResponse>,
	logout(): Promise<AxiosResponse>
	register(formData : RegisterFormDataI): Promise<AxiosResponse>
}

export interface SubmissionRepositoryI {
	apiClient: AxiosInstance
	index(page: number): Promise<AxiosResponse>
	markAsRead(submissionId: string|number): Promise<AxiosResponse>
}

export interface CalendarRepositoryI {
	apiClient: AxiosInstance
	getSlots(duration: number): Promise<AxiosResponse>
	store(schedule: ScheduleDataI): Promise<AxiosResponse>
}

export interface AppointmentFormData {
	name: string
	description: string
	startDateTime: Date
	duration: number
	price: number
	deposit: number
}

export interface AppointmentRepositoryI {
	apiClient: AxiosInstance
	index(param?: string, paramId?: string | number): Promise<AxiosResponse>
	show(appointmentId: string): Promise<AxiosResponse>
	store(
		formData: AppointmentFormData,
		submissionId: string | number | null
	): Promise<AxiosResponse>
	update(
		submissionId: string | number,
		formData: AppointmentFormData
	): Promise<AxiosResponse>
	delete(appointmentId: string | number): Promise<AxiosResponse>
	getForSubmission(submissionId: string): Promise<AxiosResponse>
}

export interface MessageRepositoryI {
	apiClient: AxiosInstance
	post(submissionId: string, message: object): Promise<AxiosResponse>
}

export interface UserRepositoryI {
	apiClient: AxiosInstance
}

export interface ClientFormData {
	first_name: string
	last_name: string
	email: string
}

export interface ClientRepositoryI {
	apiClient: AxiosInstance
	index(): Promise<AxiosResponse>
	show(clientId: string | number): Promise<AxiosResponse>
	delete(clientId: string | number): Promise<AxiosResponse>
	update(
		clientId: string | number,
		clientFormData: ClientFormData
	): Promise<AxiosResponse>
}

export interface ApiServiceInterface {
	auth: AuthRepositoryI
	users: UserRepositoryI
	submissions: SubmissionRepositoryI
	messages: MessageRepositoryI
	clients: ClientRepositoryI
}
