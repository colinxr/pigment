import createApiService from './src/apiService'

export default (accessToken = null) => createApiService({
  baseUrl: 'https://local.dayplanner.com/api',
  accessToken,
})
