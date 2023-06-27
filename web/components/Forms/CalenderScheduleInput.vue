<script setup>
const emit = defineEmits(['time-set'])

const props = defineProps({
	day: {
		type: Object,
		required: true,
	},
	error: {
		type: String,
	},
})

const openRef = ref(null)
const closeRef = ref(null)

const formattedTime = timeString => {
	if (!timeString) return null

	const [time, meridiem] = timeString.split(' ')
	let [hours, minutes] = time.split(':')

	if (meridiem === 'pm') {
		hours = (Number(hours) + 12).toString()
	}

	return `${hours.padStart(2, '0')}:${minutes}`
}

const labelText = computed(() => {
	return props.day.key.charAt(0).toUpperCase() + props.day.key.slice(1) //capitalize
})

const handleInput = (value, input) => {
	emit('time-set', {
		name: input.name,
		value: value,
	})
}
</script>

<template>
	<div>
		<div class="flex">
			<div class="w-[130px] text-right font-bold self-start pt-5">
				<label class="mr-10" :for="props.day.key">{{ labelText }}</label>
			</div>
			<div class="flex-grow">
				<div class="flex flex-grow justify-between">
					<FormKit
						ref="openRef"
						:classes="{
							outer: 'w-1/2 mr-5 ',
							label: 'text-xs',
						}"
						type="time"
						:name="`${props.day.key}_open`"
						label="Opening"
						:value="formattedTime(props.day.times.open)"
						@input="handleInput"
					/>

					<FormKit
						ref="closeRef"
						type="time"
						:classes="{
							outer: 'w-1/2 mb-0',
							label: 'text-xs',
						}"
						:name="`${props.day.key}_close`"
						label="Closing"
						:value="formattedTime(props.day.times.close)"
						@input="handleInput"
					/>
				</div>
				<div v-if="props.day.error" class="mb-5">
					<span class="text-orange-600">{{ props.day.error }}</span>
				</div>
			</div>
		</div>
	</div>
</template>
