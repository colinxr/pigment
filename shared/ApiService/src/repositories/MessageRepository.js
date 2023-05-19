export default class MessageRepository {
  constructor(apiClient) {
    this.apiClient = apiClient
  }

  async post(submissionId, message) {
    const res = await this.apiClient.post(`/submissions/${submissionId}/message`, message)

    return res.data
  }
}
