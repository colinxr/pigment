const pad = (value, length) => {
	if (value.toString().length < length) return pad(`0${value}`, length)

	return value
}

const buildOffsetISO = timezoneOffset => {
	const offsetHours = Math.abs(Math.floor(timezoneOffset / 60))
	const offsetMinutes = Math.abs(timezoneOffset % 60)
	const offsetSign = timezoneOffset >= 0 ? '+' : '-'

	return offsetSign + pad(offsetHours, 2) + pad(offsetMinutes, 2)
}

const getTimeZoneOffset = () => {
	const date = new Date()
	const timezoneOffset = date.getTimezoneOffset()

	return buildOffsetISO(timezoneOffset)
}

const getReadableDate = inputDate => {
	const date = new Date(inputDate + 'Z')

	const options = {
		year: 'numeric',
		month: 'long',
		day: 'numeric',
		hour: 'numeric',
		minute: 'numeric',
	}

	return date.toLocaleString('en-US', options)
}

const dateIsUpcoming = dateTimeString => {
	const dateTime = new Date(dateTimeString)
	const currentTime = new Date()

	return dateTime > currentTime
}

const getDuration = (startTime, endTime) => {
	const start = new Date(startTime)
	const end = new Date(endTime)
	// Calculate the difference in milliseconds
	const diffInMilliseconds = Math.abs(end.getTime() - start.getTime())
	// Convert milliseconds to hours
	const hours = Math.floor(diffInMilliseconds / (1000 * 60 * 60))

	return hours
}

const convertToUTC = timestring => {
	const date = new Date(timestring)
	return date.toISOString()
}

const convertToIsoString = (dateString, withTz = false) => {
	const dateTime = new Date(dateString)

	const offset = dateTime.getTimezoneOffset()
	const offsetHours = Math.floor(Math.abs(offset) / 60)
	// const offsetHoursString = offsetHours.toString().padStart(2, '0')
	const offsetMinutesRemainder = (Math.abs(offset) % 60)
		.toString()
		.padStart(2, '0')

	const year = dateTime.getUTCFullYear()
	const month = (dateTime.getUTCMonth() + 1).toString().padStart(2, '0')
	const day = dateTime.getUTCDate().toString().padStart(2, '0')
	const hours = dateTime.getUTCHours().toString().padStart(2, '0')
	const minutes = dateTime.getUTCMinutes().toString().padStart(2, '0')

	const isoString = `${year}-${month}-${day} ${hours}:${minutes}:00`
	return withTz
		? `${isoString}${
				offset >= 0 ? '+' : '-'
		  }${offsetHours}${offsetMinutesRemainder}`
		: isoString
}

export {
	getTimeZoneOffset,
	getReadableDate,
	dateIsUpcoming,
	getDuration,
	convertToIsoString,
	convertToUTC,
}
