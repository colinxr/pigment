export default class SubmissionRepository {
  constructor(apiClient) {
    this.apiClient = apiClient
  }

  async index(page = 1) {
    const res = await this.apiClient.get(`/submissions?page=${page}`)

    return res.data
  }
}
