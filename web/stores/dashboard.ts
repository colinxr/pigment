import { ref } from 'vue'
import { defineStore } from 'pinia'
import ApiService from '@dayplanner/apiservice'
import { ClientI, SubmissionI } from './types'

const activeSubmission = ref<SubmissionI>()
const nextPage = ref<number | null>(1)
const submissions = ref<SubmissionI[]>([])
const submissionsList = ref<number[]>([])

export default defineStore('dashboardStore', () => {
	const getNextPageFromUrl = (url: string | null): number | null => {
		if (!url) return null

		const match = url.match(/page=(\d+)/)

		return match ? Number(match[1]) : null
	}

	const setActiveSubmission = (submission: SubmissionI) => {
		activeSubmission.value = submission
	}

	const findSubmissionById = (activeSubId: number) =>
		submissions.value.find(({ id }) => id === activeSubId)

	const getSubmissions = async() => {
		if (!nextPage.value) return

		try {
			const { data } = await ApiService.submissions.index(nextPage.value)
			submissions.value = [...submissions.value, ...data.submissions.data]

			nextPage.value = getNextPageFromUrl(data.submissions.next_page_url)

			setSubmssionListOrder(submissions.value)
			console.log(submissionsList.value);
			
		} catch (error) {
			console.log(error)
		}
	}

	const setSubmssionListOrder = (submissions: SubmissionI[]) => submissionsList.value = submissions.map(({id}) => id)

	const updateSubmissionClient = (client: ClientI) => {
		if (!activeSubmission.value) return

		const submission = findSubmissionById(activeSubmission.value.id)

		if (!submission) return

		submission.client = client

		submissions.value = submissions.value.map(sub => {
			if (sub.id === submission.id) return sub

			return sub
		})

		activeSubmission.value.client = client
	}

	const updateSubmissionsListOrder = (subId: number) => {
		const reorderedList = submissionsList.value.sort((a, b) => {
			if (a === subId) return -1; // Move a to the front
			
			if (b === subId) return 1; // Move b to the front
		
			return 0; // Maintain the existing order
		});

		submissionsList.value = reorderedList

		console.log(submissionsList.value);
		
	}

	return {
		submissions,
		nextPage,
		activeSubmission,
		submissionsList,
		getSubmissions,
		setActiveSubmission,
		updateSubmissionClient,
		findSubmissionById,
		setSubmssionListOrder,
		updateSubmissionsListOrder
	}
})
