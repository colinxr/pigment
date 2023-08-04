<script setup>
import { useToast } from 'primevue/usetoast'
import Toast from 'primevue/toast'

const toast = useToast()

const { flashMessage } = useFormErrors()

watch(flashMessage, () => {
	toast.add({
		severity: 'error',
		summary: 'Error',
		detail: 'Something went wrong.',
		life: 3000,
	})
})
</script>

<template>
	<NuxtLayout>
		<Toast />
		<NuxtPage />
	</NuxtLayout>
</template>
