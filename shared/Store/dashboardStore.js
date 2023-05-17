import { defineStore } from 'pinia'
import { ref } from 'vue'
import ApiService from '@dayplanner/apiservice'

const useDashboardStore = defineStore('dashboardStore', () => {
  const submissions = ref([])

  async function getSubmissions() {
    try {
      const data = await ApiService.submissions.index()
      submissions.value = data.submissions
    } catch (error) {
      console.log(error)
    }
  }

  return {
    submissions,
    getSubmissions,
  }
})

export default useDashboardStore
