export default () => {
	const getTimeZoneOffset = () => {
		const date = new Date()
		const timezoneOffset = date.getTimezoneOffset()

		return buildOffsetISO(timezoneOffset)
	}

	const buildOffsetISO = offset =>
		`${(offset >= 0 ? '+' : '-') + pad(Math.abs(offset / 60), 2)}:${pad(
			Math.abs(offset % 60),
			2
		)}`

	return { getTimeZoneOffset }
}
