import { reactive, ref } from 'vue'

export default () => {
	const showFormAlert = ref(false)
	const formStatus = ref('')
	const alertMessage = ref('')
	const validationErrs = ref({})

	const handleResponseErrors = ({ status, data }) => {
		showFormAlert.value = true
		formStatus.value = 'error'

		if (status === 422) {
			if (!data.message) {
				alertMessage.value = data.error
				return
			}

			alertMessage.value = 'Please fix the errors below'
			validationErrs.value = buildFormErrorBag(data.errors)
			console.log(validationErrs.value)

			return
		}

		if (status === 500) {
			// flash error
			alertMessage.value = 'Something went wrong'
		}

		alertMessage.value = data.message
	}

	const buildFormErrorBag = errors => {
		const errorBag = {}

		Object.keys(errors).forEach(field => {
			errorBag[field] = errors[field]
		})

		return errorBag
	}

	return {
		validationErrs,
		showFormAlert,
		formStatus,
		alertMessage,
		handleResponseErrors,
		buildFormErrorBag,
	}
}
