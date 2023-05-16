import { reactive } from 'vue'

export default () => {
  const errorState = reactive({
    isSet: false,
    message: '',
    validationErrs: null,
  })

  const handleResponseErrors = ({ status, data }) => {
    const newErrorState = {
      isSet: true,
      message: data.message,
      validationErrs: null,
    }
  
    if (status === 422) {
      newErrorState.validationErrs = data.errors
    }
  
    Object.assign(errorState, newErrorState)
  }

  return { errorState, handleResponseErrors }
}