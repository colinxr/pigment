<template>
	<ErrorAlert v-if="status === 'error'" :msg="msg" />

	<SuccessAlert v-if="status === 'success'" :msg="msg" />

	<WarningAlert v-if="status === 'warning'" :msg="msg" />
</template>

<script setup>
import ErrorAlert from './ErrorAlert.vue'
import SuccessAlert from './SuccessAlert.vue'
import WarningAlert from './WarningAlert.vue'

const props = defineProps({
	status: {
		type: String,
		default: '',
		validator(value) {
			// The value must match one of these strings
			return ['success', 'warning', 'error'].includes(value)
		},
	},
	msg: {
		type: String,
		required: true,
	},
})
</script>
