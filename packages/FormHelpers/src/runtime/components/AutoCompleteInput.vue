<script setup>
import { onClickOutside } from '@vueuse/core'

const props = defineProps({
	context: {
		type: Object,
		required: true,
	},
})

const target = ref(null)

const inputVal = ref('')
const searchResults = ref([])
const showSearchResults = ref(false)

onClickOutside(target, () => clear())

const handleSearch = () => {
	const keysToSearch = props.context.keysToSearch

	searchResults.value = props.context.list.filter(item =>
		keysToSearch.some(key => item[key].startsWith(inputVal.value))
	)

	if (!searchResults.value.length) return

	showSearchResults.value = true
}

watch(inputVal, newVal => {
	props.context.node.input(newVal)
})

const clear = () => {
	searchResults.value = []
	showSearchResults.value = false
}

const handleSelect = item => {
	inputVal.value = item[props.context.valueToShow]
	props.context.node.input(inputVal.value)
	clear()
}
</script>

<template>
	<div class="relative">
		<div class="mt-1 flex w-full">
			<input
				ref="searchBox"
				v-model="inputVal"
				type="text"
				:name="context.node.name"
				class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-4 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
				aria-label="Search"
				@keyup="handleSearch"
			/>
		</div>
		<aside
			v-if="showSearchResults"
			ref="target"
			class="absolute p-2 z-10 w-full bg-white border rounded-md shadow-md -mt-1"
			role="menu"
			aria-labelledby="menu-heading"
		>
			<ul class="w-full">
				<li
					v-for="(item, i) in searchResults"
					:key="i"
					class="w-full cursor-pointer rounded-md px-2 py-3 space-x-2 hover:bg-blue-600 hover:text-white focus:bg-blue-600 focus:text-white focus:outline-none"
					:tabindex="i"
					@click="handleSelect(item)"
					@keypress.enter="handleSelect(item)"
					@blur="clear"
				>
					<h3 class="font-bold">{{ item[context.keysToSearch[0]] }}</h3>
					<h3 class="ml-0">{{ item[context.keysToSearch[1]] }}</h3>
				</li>
			</ul>
		</aside>
	</div>
</template>
