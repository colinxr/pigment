import { AxiosInstance, AxiosResponse } from 'axios'
import { ClientRepositoryI, ClientFormData } from '../types'

export default class ClientRepository implements ClientRepositoryI {
  apiClient: AxiosInstance

  constructor(apiClient: AxiosInstance) {
    this.apiClient = apiClient
  }

  async index(): Promise<AxiosResponse> {
    const res = await this.apiClient.get(`/clients`)

    return res
  }

  async show(clientId: string|number): Promise<AxiosResponse> {
    const res = await this.apiClient.get(`/clients/${clientId}`)

    return res
  }

  async update(clientId: string|number, formData: ClientFormData): Promise<AxiosResponse> {
    const res = await this.apiClient.put(`/clients/${clientId}`, formData)

    return res
  }

}
