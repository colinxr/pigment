<script setup>
import { storeToRefs } from 'pinia'

import useSubmissionsStore from '@/stores/submissions'
import SubmissionCard from './SubmissionCard.vue'

const submissionsStore = useSubmissionsStore()
const { findSubmissionById } = submissionsStore
const { submissionsList } = storeToRefs(submissionsStore)

const hasSubmissions = computed(() => submissionsList.value.length)
</script>

<template>
	<!-- <div class="sidebar h-screen w-96 flex-shrink-0 bg-gray-100 p-4"> -->
	<div
		class="submissions_list bg-white flex flex-col md:w-auto lg:w-1/3 h-full"
	>
		<!-- Submissions Headers -->
		<div
			class="flex flex-row items-center pt-4 px-2 md:px-4 sm:block md:hidden lg:block"
		>
			<div class="flex flex-row items-center">
				<div class="text-xl font-semibold">Messages</div>
				<!-- <div class="flex items-center justify-center ml-2 text-xs h-5 w-5 text-white bg-red-500
          rounded-full font-medium"> -->
				<!-- 5</div> -->
			</div>

			<!-- <div class="lg:ml-auto">
				<button
					class="flex items-center justify-center h-7 w-7 bg-gray-200 text-gray-500 rounded-full"
				>
					<svg
						class="w-4 h-4 stroke-current"
						fill="none"
						stroke="currentColor"
						viewBox="0 0 24 24"
						xmlns="http://www.w3.org/2000/svg"
					>
						<path
							stroke-linecap="round"
							stroke-linejoin="round"
							stroke-width="2"
							d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
						/>
					</svg>
				</button>
			</div> -->
		</div>

		<div v-if="hasSubmissions" class="mt-2">
			<SubmissionCard
				v-for="(subId, i) in submissionsList"
				:key="i"
				:submission="findSubmissionById(subId)"
			/>
		</div>
	</div>
	<!-- </div> -->
</template>
@/stores/submissions
