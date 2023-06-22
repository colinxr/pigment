import axios, { AxiosError, AxiosInstance } from 'axios'
import handleApiErrors from './handleApiErrors'

export default (baseURL: string): AxiosInstance => {
	const client = axios.create({
		baseURL,
		withCredentials: true,
		headers: {
			Accept: 'application/json',
			'Content-Type': 'application/json',
			'X-Requested-With': 'XMLHttpRequest',
		},
	})

	// if (typeof window !== 'undefined') {
	// 	const token = await getToken()

	// 	console.log(token)

	// 	client.interceptors.request.use(request => {
	// 		request.headers.set('Authorization', `Bearer ${token}`)
	// 		return request
	// 	})
	// }

	client.interceptors.response.use(
		response => response,
		error => {
			handleApiErrors(error)
			throw new AxiosError(error)
		}
	)

	return client
}
