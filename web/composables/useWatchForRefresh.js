const shouldRefreshData = ref(false)

export default () => {
	const triggerRefresh = () => {
		shouldRefreshData.value = true
	}

	return {
		shouldRefreshData,
		triggerRefresh,
	}
}
