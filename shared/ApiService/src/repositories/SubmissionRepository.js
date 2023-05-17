export default class SubmissionRepository {
  constructor(apiClient) {
    this.apiClient = apiClient
  }

  async index() {
    const res = await this.apiClient.get('submissions')

    return res.data
  }
}
