<script setup>
import ApiService from '@dayplanner/apiservice'

import { getReadableDate } from '@/composables/useDateService.js'

const props = defineProps({
	context: Object,
})

const slots = ref([])
const duration = ref(props.context.value)
const warning = ref('')
const showWarningErr = ref(false)

// const handleInput = e => {
// 	props.context.node.input(e.target.value)
// }

watch(duration, async newValue => {
	showWarningErr.value = false

	if (newValue === '') {
		slots.value = []
		return
	}

	const { data: payload } = await ApiService.calendars.getSlots(newValue)

	if (payload.data.warning) {
		warning.value = payload.data.warning
		showWarningErr.value = true
	}

	slots.value = payload.data
})
</script>
<template>
	<div>
		<input
			id="duration"
			v-model="duration"
			class="formkit-input w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-4 text-base font-mediumtext-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
			type="number"
			name="duration"
		/>
	</div>
	<div v-if="slots" class="p-2">
		<ul>
			<li v-for="(slot, i) in slots" :key="i" class="flex md:w-2/3">
				<span>{{ getReadableDate(slot.dateTime) }}</span> &nbsp;
				<span v-if="slot.message">{{ slot.message }}</span>
			</li>
		</ul>
	</div>
</template>
