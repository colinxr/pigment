/* eslint-disable prettier/prettier */
import Cookies from 'js-cookie'
import { AxiosError } from 'axios'

export default (error: AxiosError) => {
	if (error.response) {
		switch (error.response.status) {
			// Handle Bad Request errors
			case 400: {
				console.log('Bad Request:', error.response.data)
				break
			}

			// Handle Unauthorized errors
			case 401: {
				console.log('Unauthorized:', error.response.data)
				Cookies.remove('authStore')
				
				window.location.href = '/'
				break
			}

			// Handle Forbidden errors
			case 403: {
				const resJson: any = error.response.data as any
				console.log('Forbidden:', resJson)

				if (resJson.redirectURL) {
					window.location.href = resJson.redirectURL
				}

				break
			}

			// Handle Not Found errors
			case 404: {
				console.log('Not Found:', error.response)

				window.location.href = '/not-found'
				break
				// Add more cases for other error status codes as needed
			}

			// Handle Validation errors
			case 422: {
				console.log('Validation Errors:', error.response.data)

				return error.response.data
				// Add more cases for other error status codes as needed
			}
			
			// Handle other error status codes
			default: {
				console.log('Server Error:', error.response.data)

				break
			}
		}

		return error.response
	}

	// The request was made, but no response was received
	if (error.request) {
		console.log('No response received from the server:', error.request)
		return error.response
	}
	// Something happened while setting up the request
	console.log('Error:', error.message)
	return error.response
}
