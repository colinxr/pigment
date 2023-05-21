import { ref } from 'vue'
import { defineStore } from 'pinia'
import ApiService from '@dayplanner/apiservice'

type SubmissionI = {
  id: number,
  client_id: number,
  user_id: number,
  idea: string, 
  created_at: Date,
  updated_at: Date,
}

const useDashboardStore = defineStore('dashboardStore', () => {
  const submissions = ref<SubmissionI[]>([])
  const activeSubmission = ref<SubmissionI>()
  const nextPage = ref<number | null>(1)

  function getNextPageFromUrl(url: string | null): number | null {
    if (!url) return null

    const match = url.match(/page=(\d+)/)

    return match ? Number(match[1]) : null
  }

  function setActiveSubmission(submission: SubmissionI) {
    activeSubmission.value = submission
  }

  async function getSubmissions() {
    if (!nextPage.value) return

    try {
      const { data } = await ApiService.submissions.index(nextPage.value)
      submissions.value = [...submissions.value, ...data.submissions]
      
      nextPage.value = getNextPageFromUrl(data.next_page_url)
    } catch (error) {
      console.log(error)
    }
  }

  return {
    submissions,
    nextPage,
    activeSubmission,
    getSubmissions,
    setActiveSubmission,
  }
})

export default useDashboardStore
