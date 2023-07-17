import axios, { AxiosError, AxiosInstance } from 'axios'
import Cookies from 'js-cookie'
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

	if (typeof window !== 'undefined') {
			client.interceptors.request.use(request => {
				if (request.headers.Authorization) return request

				const storeCookie = Cookies.get('authStore')
				
				if (!storeCookie) return request

				const authStore = JSON.parse(storeCookie)
				
				request.headers.set('Authorization', `Bearer ${authStore.user.token}`)
				return request
			})
	}
	client.interceptors.response.use(
		response => response,
		error => {
			handleApiErrors(error)
			throw new AxiosError(error)
		}
	)

	return client
}
