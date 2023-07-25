import { AxiosInstance, AxiosResponse } from 'axios'
import { UserRepositoryI } from '../types'

export default class UserRepository implements UserRepositoryI {
	apiClient: AxiosInstance

	constructor(apiClient: AxiosInstance) {
		this.apiClient = apiClient
	}

	async exists(username: string): Promise<AxiosResponse> {
		const res = await this.apiClient.get(`/users/${username}`)

    return res
	}
}
