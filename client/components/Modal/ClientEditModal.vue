<script setup>
import useModalStore from '@/stores/modal'
import useSubmissionsStore from '@/stores/submissions'

import { DynamicForm, AlertWrapper } from '#components'

const { $apiService } = useNuxtApp()

const submissionsStore = useSubmissionsStore()

const {
	showFormAlert,
	formStatus,
	alertMessage,
	validationErrs,
	handleResponseErrors,
} = useFormErrors()

const store = useModalStore()

const props = defineProps({
	client: {
		type: Object,
		required: true,
	},
})

const formSchema = [
	{
		$formkit: 'text',
		label: 'Client First Name *',
		name: 'first_name',
		validation: 'required',
	},
	{
		$formkit: 'text',
		label: 'Client Last Name *',
		name: 'last_name',
		validation: 'required',
	},
	{
		$formkit: 'text',
		label: 'Client Email *',
		name: 'email',
		validation: 'required|email',
	},
]

const handleSubmit = async formData => {
	showFormAlert.value = false

	try {
		const res = await $apiService.clients.update(formData)

		showFormAlert.value = true
		formStatus.value = 'success'
		alertMessage.value = res.data.message || 'Updated Successfully'

		submissionsStore.updateSubmissionClient(res.data.data)

		return
	} catch (error) {
		console.log(error.message.response)

		handleResponseErrors(error.message.response)
	}
}
</script>

<template>
	<div
		class="bg-white border border-gray mx-auto w-full max-w-[550px] rounded-xl p-4"
	>
		<header>
			<div class="flex justify-between mb-2">
				<h1>Update Client</h1>
				<span class="cursor-pointer" @click="store.closeModal"> X </span>
			</div>

			<AlertWrapper
				v-if="showFormAlert"
				:status="formStatus"
				:msg="alertMessage"
			/>
		</header>

		<DynamicForm
			formId="update-client"
			:schema="formSchema"
			:data="props.client"
			:validationErrs="validationErrs"
			@form-submitted="handleSubmit"
		/>
	</div>
</template>
@/stores/submissions
