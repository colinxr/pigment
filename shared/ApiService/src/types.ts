import { AxiosInstance, AxiosResponse } from 'axios'

export interface AuthRepositoryI {
  apiClient: AxiosInstance,
  getAuthenticatedSession(): Promise<AxiosResponse>,
  /* eslint-disable-next-line */
  login(data: object): Promise<AxiosResponse>,
  logout(): Promise<AxiosResponse>,
  register(): Promise<AxiosResponse>,
  resetBaseUrl(): string,
}



export interface SubmissionRepositoryI {
  apiClient: AxiosInstance,
  index(page: number): Promise<AxiosResponse>,
  getAppointments(submissionId: number): Promise<AxiosResponse>,
}

export interface MessageRepositoryI {
  apiClient: AxiosInstance,
  /* eslint-disable-next-line */
  post(submissionId: string, message: object): Promise<AxiosResponse>,
}

export interface UserRepositoryI {
  apiClient: AxiosInstance,
}

export interface ClientFormData {
  first_name: string, 
  last_name: string,
  email: string,
}

export interface ClientRepositoryI {
  apiClient: AxiosInstance,
  /* eslint-disable-next-line */
  update(clientFormData: ClientFormData): Promise<AxiosResponse>,
}



export interface ApiServiceInterface {
  auth: AuthRepositoryI,
  users: UserRepositoryI,
  submissions: SubmissionRepositoryI,
  messages: MessageRepositoryI,
  clients: ClientRepositoryI,
}


