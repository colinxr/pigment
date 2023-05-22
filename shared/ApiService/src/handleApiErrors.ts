import { AxiosError, AxiosResponse } from 'axios'

export default (error: AxiosError) => {
  if (error.response) {
    // Server responded with an error status code
    const { status, data } = error.response
    
    switch (status) {
      case 400:
        // Handle Bad Request errors
        console.log('Bad Request:', data)
        break
      case 401:
        // Handle Unauthorized errors
        console.log('Unauthorized:', data)
        break
      case 404:
        // Handle Not Found errors
        console.log('Not Found:', data)
        break
      // Add more cases for other error status codes as needed
      default:
        // Handle other error status codes
        console.log('Server Error:', data)
        
        break
    }

    return error.response
  } if (error.request) {
    // The request was made, but no response was received
    console.log('No response received from the server:', error.request)
    return error.response
  }
  // Something happened while setting up the request
  console.log('Error:', error.message)
  return error.response
}
