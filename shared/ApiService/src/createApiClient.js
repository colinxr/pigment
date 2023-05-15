import axios from 'axios'
import handleApiErrors from './handleApiErrors'

export default ({ baseURL, accessToken }) => {
  const headers = {
    Accept: 'application/json',
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  }

  if (accessToken) {
    headers.Authorization = `Bearer ${accessToken}`
  }

  const client = axios.create({
    baseURL,
    headers,
    withCredentials: true,
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
