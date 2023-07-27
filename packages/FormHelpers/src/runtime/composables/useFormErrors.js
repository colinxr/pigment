import { reactive, ref } from 'vue'

export default () => {
	const showFormAlert = ref(false)
	const formStatus = ref('')
	const alertMessage = ref('')
	const validationErrs = ref({})

	const handleResponseErrors = ({ status, data }) => {
		showFormAlert.value = true
		formStatus.value = 'error'
		alertMessage.value = data.message

		if (status === 422) {
			validationErrs.value = buildFormErrorBag(data.errors)
			return
		}

		if (status === 500) {
			// flash error
			alertMessage.value = 'Something went wrong'
		}
	}

	const buildFormErrorBag = errors => {
		console.log(errors)
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
