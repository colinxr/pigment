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

  // client.interceptors.request.use(async (config) => {
  //   if (
  //     ['post', 'put', 'delete'].includes(config.method)
  //     && !Cookies.get('XSRF-TOKEN')) {
  //     const res = await client.get('/sanctum/csrf-token', {
  //       baseURL: 'https://local.dayplanner.com',
  //     })

  //     return config
  //   }
  //   return config
  // }, null)

  client.interceptors.response.use(
    (response) => response,
    (error) => {
      handleApiErrors(error)
      return Promise.reject(error)
    },
  )

  return client
}
