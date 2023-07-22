/* eslint-disable prettier/prettier */
import { AxiosError } from 'axios'

import Cookies from 'js-cookie'

export default (error: AxiosError) => {
	if (error.response) {
		switch (error.response.status) {
		case 400: {
			// Handle Bad Request errors
			console.log('Bad Request:', error.response.data)
			break
		}
		case 401: {
			// Handle Unauthorized errors
			console.log('Unauthorized:', error.response.data)
			Cookies.remove('authStore')
			
			window.location.href = '/'
			break
		}
		case 403: {
			// Handle Forbidden errors
			const resJson: any = error.response.data as any
			console.log('Forbidden:', resJson)

			if (resJson.redirectURL) {
				window.location.href = resJson.redirectURL
			}

			break
		}
		case 404: {
			// Handle Not Found errors
			console.log('Not Found:', error.response)

			window.location.href = '/not-found'
			break
			// Add more cases for other error status codes as needed
		}
		default: {
			// Handle other error status codes
			console.log('Server Error:', error.response.data)
			break
		}
		}

		return error.response
	}

	if (error.request) {
		// The request was made, but no response was received
		console.log('No response received from the server:', error.request)
		return error.response
	}
	// Something happened while setting up the request
	console.log('Error:', error.message)
	return error.response
}
