import { AxiosInstance, AxiosResponse } from 'axios'
import { CalendarRepositoryI } from '../types'

export default class CalendarRepository implements CalendarRepositoryI {
	apiClient: AxiosInstance

	constructor(apiClient: AxiosInstance) {
		this.apiClient = apiClient
	}

	async getSlots(duration: number): Promise<AxiosResponse<any, any>> {
		const res = await this.apiClient.get(
			`/calendars/slots?duration=${duration}`
		)

		return res
	}
}
