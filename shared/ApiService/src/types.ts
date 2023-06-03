import { AxiosInstance, AxiosResponse } from 'axios'

export interface AuthRepositoryI {
  apiClient: AxiosInstance,
  getAuthenticatedSession(): Promise<AxiosResponse>,
  /* eslint-disable-next-line */
  login(data: object): Promise<AxiosResponse>,
  logout(): Promise<AxiosResponse>,
  register(): Promise<AxiosResponse>,
}



export interface SubmissionRepositoryI {
  apiClient: AxiosInstance,
  index(page: number): Promise<AxiosResponse>,
}

export interface AppointmentFormData {
  name: string,
  description: string,
  startDateTime: Date,
  duration: number,
  price: number,
  deposit: number,
}

export interface AppointmentRepositoryI {
  apiClient: AxiosInstance,
  get(): Promise<AxiosResponse>,
  store(submissionId: number, formData: AppointmentFormData): Promise<AxiosResponse>,
  getForSubmission(submissionId: number): Promise<AxiosResponse>,
}

export interface MessageRepositoryI {
  apiClient: AxiosInstance,
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
  update(clientFormData: ClientFormData): Promise<AxiosResponse>,
}

export interface ApiServiceInterface {
  auth: AuthRepositoryI,
  users: UserRepositoryI,
  submissions: SubmissionRepositoryI,
  messages: MessageRepositoryI,
  clients: ClientRepositoryI,
}


