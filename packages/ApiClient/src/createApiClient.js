import axios from 'axios'
import handleApiErrors from './handleApiErrors'

export default ({ baseUrl, accessToken }) => {
  const headers = {
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  }

  if (accessToken) {
    headers.Authorization = `Bearer ${accessToken}`
  }

  const client = axios.create({
    baseUrl,
    headers,
    withCredentials: true,
  })

  client.interceptors.response.use(
    (response) => response,
    (error) => {
      handleApiErrors(error)
      return Promise.reject(error)
    },
  )
}
