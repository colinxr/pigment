import { AxiosInstance, AxiosResponse } from 'axios'
import { CalendarRepositoryI, ScheduleDataI } from '../types'



export default class CalendarRepository implements CalendarRepositoryI {
	apiClient: AxiosInstance

	constructor(apiClient: AxiosInstance) {
		this.apiClient = apiClient
	}

	async getSlots(duration: number): Promise<AxiosResponse> {
		const res = await this.apiClient.get(
			`/calendars/slots?duration=${duration}`
		)

		return res
	}

	async store(schedule: ScheduleDataI): Promise<AxiosResponse> {
		const res = await this.apiClient.post(`/calendars/schedule`, schedule)

		return res
	}

	async show(): Promise<AxiosResponse> {
		const res = await this.apiClient.get(`/calendars/schedule`)
		
		return res
	}
}
