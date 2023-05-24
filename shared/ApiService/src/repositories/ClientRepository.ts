import { AxiosInstance, AxiosResponse } from 'axios'
import { ClientRepositoryI, ClientFormData } from '../types'

export default class ClientRepository implements ClientRepositoryI {
  apiClient: AxiosInstance

  constructor(apiClient: AxiosInstance) {
    this.apiClient = apiClient
  }

  async update(formData: ClientFormData): Promise<AxiosResponse> {
    const res = await this.apiClient.put('/clients', formData)

    return res
  }
}
