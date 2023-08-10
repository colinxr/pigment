import { AxiosInstance, AxiosResponse } from 'axios'
import { MessageRepositoryI } from '../types'

export default class MessageRepository implements MessageRepositoryI {
	apiClient: AxiosInstance

	constructor(apiClient: AxiosInstance) {
		this.apiClient = apiClient
	}

	async post(submissionId: string, message: object): Promise<AxiosResponse> {
		const res = await this.apiClient.post(
			`/submissions/${submissionId}/message`,
			message
		)

		return res
	}

	async storeTempFiles(files: Array<File>): Promise<AxiosResponse> {
		const formData = new FormData();
		
		for (const file of files) {
			console.log(file);
			
			formData.append('attachments[]', file, file.name);
		}
		
		const res = await this.apiClient.post(`/messages/attachments/temp`, formData, 
		{
			headers: { 'Content-Type': 'multipart/form-data', },
		})

		return res
	}
}
