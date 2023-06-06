import { AxiosInstance, AxiosResponse } from 'axios'
import { AppointmentRepositoryI, AppointmentFormData } from '../types'

export default class AppointmentRepository implements AppointmentRepositoryI {
  apiClient: AxiosInstance

  constructor(apiClient: AxiosInstance) {
    this.apiClient = apiClient
  }

  async store(submissionId: string, formData: AppointmentFormData): Promise<AxiosResponse> {
    const res = await this.apiClient.post(`/submissions/${submissionId}/appointments`, formData)

    return res
  }

  async getForSubmission(submissionId: string): Promise<AxiosResponse> {
    const res = await this.apiClient.get(`/submissions/${submissionId}/appointments`)

    return res
  }

  async index(): Promise<AxiosResponse> {
    const res = await this.apiClient.get(`/appointments`)

    return res
  }

  async show(appointmentId: string): Promise<AxiosResponse> {
    const res = await this.apiClient.get(`/appointments/${appointmentId}`)

    return res
  }

  async update(appointmentId: string, formData: AppointmentFormData): Promise<AxiosResponse> {
    const res = await this.apiClient.put(`/appointments/${appointmentId}`, formData)

    return res
  }

  async delete(appointmentId: string): Promise<AxiosResponse> {
    const res = await this.apiClient.delete(`/appointments/${appointmentId}`)

    return res
  }
}