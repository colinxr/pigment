<script setup>
import ApiService from '@dayplanner/apiservice'
import useAuthStore from '@/stores/auth'

import CalenderScheduleInput from './CalenderScheduleInput.vue'

const authStore = useAuthStore()
const { errorState, handleResponseErrors } = useFormErrors()

const props = defineProps({
	fetchSchedule: {
		type: Boolean,
		default: false,
	},
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
	wednesday: {
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

const days = ref(null)

onBeforeMount(async () => {
	if (!props.fetchSchedule) return

	const res = await ApiService.calendars.show()

	if (!res.data.data) return

	schedule.value = { ...schedule.value, ...res.data.data }

	days.value = buildScheduleData(schedule.value)
})

const buildScheduleData = times => {
	return Object.keys(times).map(day => ({
		key: day,
		times: times[day],
	}))
}

const updateModel = ({ name, value }) => {
	const [day, key] = name.split('_')

	schedule.value[day][key] = value
}

const handleSubmit = async formData => {
	try {
		const response = await ApiService.calendar.store(formData)

		if (response.status !== 200) {
			return handleResponseErrors(response)
		}
	} catch (error) {
		console.log(error)
		errorState.isSet = true
		errorState.message = 'Something went wrong'
		return false
	}
}
</script>

<template>
	<form v-if="days" @submit.prevent="handleSubmit">
		<CalenderScheduleInput
			v-for="(day, i) in days"
			:day="day"
			:key="i"
			@time-set="updateModel"
		/>

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
</template>
