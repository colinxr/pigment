import { AxiosInstance, AxiosResponse } from 'axios'
import { AppointmentRepositoryI, AppointmentFormData } from '../types'

export default class AppointmentRepository implements AppointmentRepositoryI {
  apiClient: AxiosInstance

  constructor(apiClient: AxiosInstance) {
    this.apiClient = apiClient
  }

  async store(submissionId: number, formData: AppointmentFormData): Promise<AxiosResponse> {
    const res = await this.apiClient.post(`/submissions/${submissionId}/appointments`, formData)

    return res
  }

  async getForSubmission(submissionId: number): Promise<AxiosResponse> {
    const res = await this.apiClient.get(`/submissions/${submissionId}/appointments`)

    return res
  }

  async get(): Promise<AxiosResponse> {
    const res = await this.apiClient.get(`/appointments`)

    return res
  }
}