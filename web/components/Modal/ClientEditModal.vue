<script setup>
import ApiService from '@dayplanner/apiservice'
import useModalStore from '@/stores/modal'
import useFormErrors from '@/composables/useFormErrors'
import DynamicForm from '@/components/Forms/DynamicForm.vue'
import AlertWrapper from '@/components/Alerts/AlertWrapper.vue'

import useDashboardStore from '@/stores/dashboard'

const dashboardStore = useDashboardStore()

const { errorState, handleResponseErrors } = useFormErrors()

const store = useModalStore()

const props = defineProps({
	client: {
		type: Object,
		required: true,
	},
})

const showFormAlert = ref(false)
const formStatus = ref('')
const alertMessage = ref('')

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
		const res = await ApiService.clients.update(formData)

		if (res.status !== 200) {
			handleResponseErrors(res)
		}

		showFormAlert.value = true
		formStatus.value = 'success'
		alertMessage.value = res.data.message || 'Updated Successfully'

		dashboardStore.updateSubmissionClient(res.data.data)

		return
	} catch (error) {
		console.log(error)

		alertMessage.value = 'something went wrong'
		formStatus.value = 'error'
		showFormAlert.value = true
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
			:errorState="errorState"
			@form-submitted="handleSubmit"
		/>
	</div>
</template>
