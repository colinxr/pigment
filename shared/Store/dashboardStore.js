import { defineStore } from 'pinia'
import { ref } from 'vue'
import ApiService from '@dayplanner/apiservice'

const useDashboardStore = defineStore('dashboardStore', () => {
  const submissions = ref([])
  const activeSubmission = ref(null)

  async function getSubmissions() {
    try {
      const data = await ApiService.submissions.index()
      submissions.value = data.submissions
    } catch (error) {
      console.log(error)
    }
  }

  function setActiveSubmission(submission) {
    activeSubmission.value = submission
  }

  return {
    submissions,
    activeSubmission,
    getSubmissions,
    setActiveSubmission,
  }
})

export default useDashboardStore
