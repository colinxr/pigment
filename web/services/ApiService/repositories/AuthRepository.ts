import { AxiosInstance, AxiosResponse } from 'axios'
import { AuthRepositoryI, RegisterFormDataI } from '../types'
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
		const csrfResponse = await this.apiClient.get('/csrf-cookie')
		
		const response = await this.apiClient.post('/login', { email, password })

		const { token } = response.data

		this.apiClient.defaults.headers.common.Authorization = `Bearer ${token}`
		return response
	}

	async logout(): Promise<AxiosResponse> {
		const res = await this.apiClient.get('/logout')

		return res
	}

	async register(formData: RegisterFormDataI): Promise<AxiosResponse> {
		const res = await this.apiClient.post('/register', formData)

		return res
	}

	async setOAuthToken(code: string): Promise<AxiosResponse> {
		const res = await this.apiClient.post('/oauth/google/callback', { code })
		return res
	}
}
