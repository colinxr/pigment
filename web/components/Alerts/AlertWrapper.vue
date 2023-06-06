<template>
	<SuccessAlert v-if="status === 'success'" :msg="msg" />

	<ErrorAlert v-if="status === 'error'" :msg="msg" />

	<WarningAlert v-if="status === 'warning'" :msg="msg" />
</template>

<script setup>
import SuccessAlert from '@/components/Alerts/SuccessAlert.vue'
import ErrorAlert from '@/components/Alerts/ErrorAlert.vue'

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
