import apiService from '../createApiClient'

class BaseRepository {
  constructor() {
    this.apiClient = apiService()
  }
}

export default new BaseRepository()
