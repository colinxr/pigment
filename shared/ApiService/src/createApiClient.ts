import axios, { AxiosInstance } from 'axios'
import handleApiErrors from './handleApiErrors'

export default (baseURL: string): AxiosInstance => {
  const client = axios.create({
    baseURL,
    withCredentials: true,
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
    },
  })

  client.interceptors.response.use(
    (response) => response,
    (error) => {
      handleApiErrors(error)
      return error.response
    },
  )

  return client
}
