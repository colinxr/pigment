import { AxiosInstance, AxiosResponse } from 'axios'
import { AuthRepositoryI } from '../types'
declare global {
	interface Window {
		sessionStorage: Storage
	}
}

export default class AuthRepository implements AuthRepositoryI {
	apiClient: AxiosInstance

	constructor(apiClient: AxiosInstance) {
		this.apiClient = apiClient
	}

	async getAuthenticatedSession() {
		try {
			const res = await this.apiClient.get('/user')

			if (res.status !== 200) return false

			return res.data
		} catch (err) {
			return false
		}
	}

	async login({
		email,
		password,
	}: {
		email: string
		password: string
	}): Promise<AxiosResponse> {
		const response = await this.apiClient.post('/login', { email, password })

		const { token } = response.data
		console.log(token)

		this.apiClient.defaults.headers.common.Authorization = `Bearer ${token}`
		return response
	}

	async logout(): Promise<AxiosResponse> {
		const res = await this.apiClient.get('/logout', {})

		return res
	}

	async register(): Promise<AxiosResponse> {
		const res = await this.apiClient.post('/register')

		return res
	}

	async setOAuthToken(code: string): Promise<AxiosResponse> {
		const res = await this.apiClient.post('/oauth/google/callback', { code })
		return res
	}
}
