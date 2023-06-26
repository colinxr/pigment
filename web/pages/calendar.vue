<script setup>
import ApiService from '@dayplanner/apiservice'
import useAuthStore from '@/stores/auth'

const authStore = useAuthStore()
const { errorState, handleResponseErrors } = useFormErrors()

definePageMeta({
	middleware: 'user-is-authenticated',
	layout: 'auth',
})

const schedule = ref({
	sunday: {
		open: null,
		close: null,
	},
	monday: {
		open: null,
		close: null,
	},
	tuesday: {
		open: null,
		close: null,
	},
	thursday: {
		open: null,
		close: null,
	},
	friday: {
		open: null,
		close: null,
	},
	saturday: {
		open: null,
		close: null,
	},
})

const days = computed(() => {
	return Object.keys(schedule.value).map(day => ({
		key: day,
		label: day.charAt(0).toUpperCase() + day.slice(1), //capitalize
	}))
})

const handleSubmit = async formData => {
	try {
		const response = await ApiService.calendar.store(formData)

		if (response.status !== 200) {
			return handleResponseErrors(response)
		}
		const { user, token } = response.data

		authStore.setUser({ ...user, token })

		return navigateTo('/app')
	} catch (error) {
		console.log(error)
		errorState.isSet = true
		errorState.message = 'Something went wrong'
		return false
	}
}
</script>

<template>
	<div class="px-5 md:w-1/2 w-full">
		<form class="w-1/4" @submit.prevent="handleSubmit">
			<div v-for="(day, i) in days" class="flex">
				<label :for="day.key">{{ day.label }}</label>
				<div class="flex">
					<FormKit
						type="time"
						label="Opening"
						v-model="schedule[day.key].open"
					/>

					<FormKit
						type="time"
						label="Opening"
						v-model="schedule[day.key].close"
					/>
				</div>
			</div>

			<button
				class="hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-8 text-base font-semibold text-white outline-none"
				type="submit"
			>
				Submit
			</button>

			<div
				v-if="errorState.isSet"
				class="alert alert-error shadow-lg w-full max-w-xs mt-5"
			>
				<div>
					<span>{{ errorState.message }}</span>
				</div>
			</div>
		</form>
	</div>
</template>
