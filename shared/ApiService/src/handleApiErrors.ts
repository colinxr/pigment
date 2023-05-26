import { AxiosError, AxiosResponse } from 'axios'

type responseData = {
  status: number,
  message: string,
  data: string,
}

export default (error: AxiosError) => {
  if (error.response) {
    // Server responded with an error status code 
    
    console.log(error.response)

    switch (error.response.status) {
      case 400:
        // Handle Bad Request errors
        console.log('Bad Request:', error.response.data)
        break
      case 401:
        // Handle Unauthorized errors
        console.log('Unauthorized:', error.response.data)
        break
      case 403:
          // Handle Forbidden errors
          
          const resJson: any = (error.response.data as any)
          console.log('Forbidden:', resJson)

          window.location.href = resJson.data
          break
      case 404:
        // Handle Not Found errors
        console.log('Not Found:', error.response.data)
        break
      // Add more cases for other error status codes as needed
      default:
        // Handle other error status codes
        console.log('Server Error:', error.response.data)
        
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
