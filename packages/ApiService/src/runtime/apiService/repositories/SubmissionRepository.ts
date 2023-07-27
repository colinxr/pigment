import { AxiosInstance, AxiosResponse } from 'axios'
import { SubmissionRepositoryI, SubmissionFormData } from '../types'

export default class SubmissionRepository implements SubmissionRepositoryI {
  apiClient: AxiosInstance

  constructor(apiClient: AxiosInstance) {
    this.apiClient = apiClient
  }

  async index(page = 1): Promise<AxiosResponse> {
    const res = await this.apiClient.get(`/submissions?page=${page}`)

    return res
  }

  async markAsRead(submissionId: string|number): Promise<AxiosResponse> {
    const res = await this.apiClient.get(`/submissions/${submissionId}/read`)

    return res
  }

  async store(username: string, formData: SubmissionFormData): Promise<AxiosResponse> {
    const res = await this.apiClient.post(`/users/${username}/submissions`, formData)

    return res
  }
}
