<script setup>
const emit = defineEmits(['time-set'])

const props = defineProps({
	day: {
		type: Object,
		required: true,
	},
})

const openRef = ref(null)
const closeRef = ref(null)

onMounted(() => {
	const { open, close } = props.day.times

	if (!open || !close) return

	// openRef.value.node.input(formattedTime(open))
	// closeRef.value.node.input(formattedTime(close))
})

// watch([open, close], ([newOpen, newClose]) => {
// 	emit('time-set', {
// 		model: newOpen ? 'open' : 'close',
// 		value: newOpen ? newOpen.value : newClose.value,
// 	})
// })

const formattedTime = timeString => {
	if (!timeString) return null

	const [time, meridiem] = timeString.split(' ')
	const [hours, minutes] = time.split(':')

	return `${hours.padStart(2, '0')}:${minutes}`
}

const labelText = computed(() => {
	return props.day.key.charAt(0).toUpperCase() + props.day.key.slice(1) //capitalize
})

const handleevt = (value, input) => {
	console.log(value)
	console.log(input)

	emit('time-set', {
		name: input.name,
		value: value,
	})
}
</script>

<template>
	<div class="flex">
		<div class="w-[130px] text-right font-bold">
			<label class="mr-10" :for="props.day.key">{{ labelText }}</label>
		</div>
		<div class="flex">
			<FormKit
				ref="openRef"
				class="mr-5"
				type="time"
				:name="`${props.day.key}_open`"
				label="Opening"
				:value="formattedTime(props.day.times.open)"
				@input="handleevt"
			/>

			<FormKit
				ref="closeRef"
				type="time"
				:name="`${props.day.key}_close`"
				label="Closing"
				:value="formattedTime(props.day.times.close)"
				@input="handleevt"
			/>
		</div>
	</div>
</template>
