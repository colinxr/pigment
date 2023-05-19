import { defineStore } from 'pinia'
import { ref } from 'vue'
import ApiService from '~/../shared/ApiService/'

const useDashboardStore = defineStore('dashboardStore', () => {
  const submissions = ref([])
  const activeSubmission = ref(null)
  const nextPage = ref(1)

  function getNextPageFromUrl(url) {
    if (!url) return null

    const match = url.match(/page=(\d+)/)

    return match ? match[1] : null
  }

  function setActiveSubmission(submission) {
    activeSubmission.value = submission
  }

  async function getSubmissions() {
    if (!nextPage.value) return

    try {
      const { submissions: respData } = await ApiService.submissions.index(nextPage.value)
      submissions.value = [...submissions.value, ...respData.data]
      nextPage.value = getNextPageFromUrl(respData.next_page_url)
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
