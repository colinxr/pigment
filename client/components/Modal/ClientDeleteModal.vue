<script setup>
import ApiService from '@pigment/api-service'
import useModalStore from '@/stores/modal'
import useWatchForRefresh from '@/composables/useWatchForRefresh'

import AlertWrapper from '@/components/Alerts/AlertWrapper.vue'

const { showFormAlert, formStatus, alertMessage, handleResponseErrors } =
	useFormErrors()

const store = useModalStore()

const { triggerRefresh } = useWatchForRefresh()

const props = defineProps({
	client: {
		type: Object,
		required: true,
	},
})

const handleConfirm = async () => {
	try {
		const res = await ApiService.clients.delete(props.client.id)

		if (res.status !== 200) handleResponseErrors(res)

		showFormAlert.value = true
		formStatus.value = 'success'
		alertMessage.value = res.data.message || 'Client Deleted'

		// emit response to trigger refetch appointments list.
		triggerRefresh()

		setTimeout(() => handleCancel(), 1000)
		return
	} catch (error) {
		console.log(error)

		if (error.response?.status === 403) return

		alertMessage.value = 'Something went wrong'
		formStatus.value = 'error'
		showFormAlert.value = true
	}
}

const handleCancel = () => store.closeModal()
</script>

<template>
	<Card class="mx-auto max-w-[400px] rounded-xl">
		<template #content>
			<p>Are you sure you want to delete this Client?</p>
			<!-- <p>The client will receive an email notifying them of the change.</p> -->

			<div class="mt-4 flex justify-end">
				<template v-if="formStatus !== 'success'">
					<Button
						class="mr-3"
						severity="secondary"
						label="Cancel"
						@click="handleCancel"
					/>

					<Button label="Confirm" @click="handleConfirm" />
				</template>

				<AlertWrapper
					v-if="showFormAlert"
					:status="formStatus"
					:msg="alertMessage"
				/>
			</div>
		</template>
	</Card>
</template>
