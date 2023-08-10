<script setup>
import useSubmissionsStore from '@/stores/submissions'
import Badge from '@/components/Badge.vue'

const router = useRouter()
const submissionsStore = useSubmissionsStore()

const props = defineProps({
	submission: {
		type: Object,
		required: true,
	},
})

const handleClick = () => {
	submissionsStore.setActiveSubmission(props.submission.id)

	router.push({ query: { as: props.submission.id } })
}
</script>

<template>
	<div
		class="sub-card flex flex-col hover:cursor-pointer hover:bg-slate-100 border-gray-100 border-b px-2 md:px-4"
		@click="handleClick"
	>
		<div class="relative flex md:block lg:flex flex-row items-center py-3">
			<!-- timestamp -->
			<div class="absolute text-xs text-gray-500 right-0 top-0 mr-4 mt-3">
				<!-- {{ timestamp }} -->
			</div>

			<!-- client avatar -->
			<div
				class="flex items-center self-center justify-center h-10 w-10 rounded-full bg-pink-500 text-pink-300 font-bold flex-shrink-0"
			>
				{{ submission.client.initials }}
			</div>

			<!-- submission body -->
			<div class="flex flex-col flex-grow ml-3 md:hidden lg:block">
				<div
					class="text-sm font-medium"
					:class="{ 'font-bold': submission.has_new_messages }"
				>
					{{ submission.client.full_name }}
					<Badge :status="submission.status" />
				</div>
				<div
					class="text-xs truncate w-40"
					:class="{ 'font-bold': submission.has_new_messages }"
				>
					{{ submission.last_message?.preview }}
				</div>
			</div>

			<!-- new messages indicator -->
			<div class="flex-shrink-0 ml-2 self-end mb-1 hidden lg:block">
				<span
					class="flex items-center justify-center h-5 w-5 bg-red-500 text-white text-xs rounded-full"
					>5</span
				>
			</div>
		</div>
	</div>
</template>
