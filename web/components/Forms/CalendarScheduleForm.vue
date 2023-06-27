<script setup>
import ApiService from '@dayplanner/apiservice'

import CalenderScheduleInput from './CalenderScheduleInput.vue'
import AlertWrapper from '@/components/Alerts/AlertWrapper.vue'

const { showFormAlert, formStatus, alertMessage, handleResponseErrors } =
	useFormErrors()

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

const errors = ref({})

const days = ref(null)

onBeforeMount(async () => {
	if (!props.fetchSchedule) return

	const res = await ApiService.calendars.show()

	if (res.data.data) {
		schedule.value = { ...schedule.value, ...res.data.data }
	}

	days.value = buildScheduleData(schedule.value)
})

const buildScheduleData = times => {
	return Object.keys(times).map(day => ({
		key: day,
		times: times[day],
		error: null,
	}))
}

const updateModel = ({ name, value }) => {
	const [day, key] = name.split('_')
	schedule.value[day][key] = value

	handleErrors(day, schedule.value[day])
}

const handleErrors = (dayName, scheduleDay) => {
	const { open, close } = scheduleDay

	const index = days.value.findIndex(({ key }) => key === dayName)

	console.log(typeof open, typeof close)
	if (
		(open === null && close === null) ||
		(typeof open === 'string' && typeof close === 'string')
	) {
		days.value[index].error = ''
		return
	}

	days.value[index].error =
		'Make sure to fill out both opening and closing hours.'
}

const handleSubmit = async formData => {
	showFormAlert.value = false

	try {
		const res = await ApiService.calendars.store({ schedule: schedule.value })
		if (res.status !== 201) handleResponseErrors(res)

		console.log(res)

		showFormAlert.value = true
		formStatus.value = 'success'
		alertMessage.value = res.data.message || 'Schedule updated'
	} catch (error) {
		console.log(error)

		if (error.response?.status === 403) return

		alertMessage.value = 'Something went wrong'
		formStatus.value = 'error'
		showFormAlert.value = true
	}
}
</script>

<template>
	<div>
		<AlertWrapper
			v-if="showFormAlert"
			:status="formStatus"
			:msg="alertMessage"
		/>
		<form v-if="days" @submit.prevent="handleSubmit">
			<CalenderScheduleInput
				v-for="(day, i) in days"
				:day="day"
				:key="i"
				@time-set="updateModel"
				class="ml-auto mr-auto"
			/>

			<button
				class="hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-8 text-base font-semibold text-white outline-none"
				type="submit"
			>
				Submit
			</button>
		</form>
	</div>
</template>
