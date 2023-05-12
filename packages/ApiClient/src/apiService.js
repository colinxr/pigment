import axios from 'axios'

export default ({ baseUrl, accessToken }) => {
  const headers = {
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  }

  if (accessToken) {
    headers.authorization = `Bearer ${accessToken}`
  }

  return axios.create({
    baseUrl,
    headers,
    withCredentials: true,
  })
}
