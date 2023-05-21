import { AxiosInstance, AxiosResponse } from 'axios'
import { MessageRepositoryI } from '../types'

export default class MessageRepository implements MessageRepositoryI {
  apiClient: AxiosInstance

  constructor(apiClient: AxiosInstance) {
    this.apiClient = apiClient
  }

  async post(submissionId: string, message: object): Promise<AxiosResponse> {
    const res = await this.apiClient.post(`/submissions/${submissionId}/message`, message)

    return res
  }
}
