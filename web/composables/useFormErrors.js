import { reactive } from 'vue'

export default () => {
	const errorState = reactive({
		isSet: false,
		message: '',
		validationErrs: null,
		errors: [],
	})

	const showFormAlert = ref(false)
	const formStatus = ref('')
	const alertMessage = ref('')

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

	const buildFormErrorBag = validationErrs => {
		const errorBag = {}

		Object.keys(validationErrs).forEach(field => {
			errorBag[field] = newErrors[field]
		})

		return errorBag
	}

	return {
		errorState,
		showFormAlert,
		formStatus,
		alertMessage,
		handleResponseErrors,
		buildFormErrorBag,
	}
}
