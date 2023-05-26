import { AxiosInstance, AxiosResponse } from 'axios'
import { SubmissionRepositoryI } from '../types'

export default class SubmissionRepository implements SubmissionRepositoryI {
  apiClient: AxiosInstance

  constructor(apiClient: AxiosInstance) {
    this.apiClient = apiClient
  }

  async index(page = 1): Promise<AxiosResponse> {
    const res = await this.apiClient.get(`/submissions?page=${page}`)

    return res
  }
}
