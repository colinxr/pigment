import { reactive } from 'vue'

export default () => {
	const shouldRefreshData = ref(false)

	const triggerRefresh = () => {
		shouldRefreshData.value = true
	}

	return {
		shouldRefreshData,
		triggerRefresh,
	}
}
