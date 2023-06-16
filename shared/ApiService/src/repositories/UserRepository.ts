import { AxiosInstance } from 'axios'
import { UserRepositoryI } from '../types'

export default class UserRepository implements UserRepositoryI {
	apiClient: AxiosInstance

	constructor(apiClient: AxiosInstance) {
		this.apiClient = apiClient
	}
}
