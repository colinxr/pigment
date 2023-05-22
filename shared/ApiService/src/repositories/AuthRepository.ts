import { AxiosInstance, AxiosResponse } from 'axios'
import { AuthRepositoryI } from '../types'

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

  async login({ email, password }: { email: string, password: string}): Promise<AxiosResponse> {
    const res = this.apiClient.get('/csrf-cookie').then(async (resp) => {
      const response = await this.apiClient.post('/login', { email, password }, {
        baseURL: this.resetBaseUrl(),
      })
      return response
    })

    return res
  }

  async logout(): Promise<AxiosResponse> {
    const res = await this.apiClient.post('/logout', {}, {
      baseURL: this.resetBaseUrl(),
    })

    return res
  }

  async register(): Promise<AxiosResponse> {
    const res = await this.apiClient.post('/register', {
      baseURL: this.resetBaseUrl(),
    })

    return res
  }

  resetBaseUrl(): string {
    return this.apiClient.defaults.baseURL!.slice(0, -4)
  }
}
