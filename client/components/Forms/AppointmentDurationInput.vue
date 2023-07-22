<script setup>
import { getReadableDate } from '@/composables/useDateService.js'
import useAppointmentSchema from '@/composables/useAppointmentSchema'

const { $apiService } = useNuxtApp()
const { setStartDateTime } = useAppointmentSchema()

const props = defineProps({
	context: Object,
})

const slots = ref([])
const duration = ref(props.context.value)
const warning = ref('')
const showWarningErr = ref(false)
const showSlots = ref(false)

watch(duration, async newValue => {
	showWarningErr.value = false

	if (newValue === '') {
		slots.value = []
		return
	}

	const { data: payload } = await $apiService.calendars.getSlots(newValue)

	if (payload.data.warning) {
		warning.value = payload.data.warning
		showWarningErr.value = true
	}

	slots.value = payload.data
	showSlots.value = true
})

const clear = () => {
	showSlots.value = false
}

const handleSelect = slot => {
	setStartDateTime(slot.dateTime)
	clear()
}
</script>
<template>
	<div class="relative">
		<div>
			<input
				id="duration"
				v-model="duration"
				:disabled="context.disabled"
				class="formkit-input w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-4 text-base font-mediumtext-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
				type="number"
				name="duration"
			/>
		</div>
		<div
			v-if="showSlots"
			class="absolute p-2 z-10 bg-white border rounded-md shadow-md -mt-1 w-full"
		>
			<ul class="w-full">
				<li
					v-for="(slot, i) in slots"
					:key="i"
					:tabindex="i"
					class="w-full cursor-pointer rounded-md px-2 py-3 space-x-2 hover:bg-blue-600 hover:text-white focus:bg-blue-600 focus:text-white focus:outline-none"
					@click="handleSelect(slot)"
					@keypress.enter="handleSelect(slot)"
					@blur="clear"
				>
					<h3 class="font-bold">{{ getReadableDate(slot.dateTime) }}</h3>
					<h4 v-if="slot.message" class="ml-0">{{ slot.message }}</h4>
				</li>
			</ul>
		</div>
	</div>
</template>
