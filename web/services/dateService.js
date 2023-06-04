const pad = (value, length) =>
  value.toString().length < length ? pad(`0${value}`, length) : value

const buildOffsetISO = timezoneOffset => {
  const offsetHours = Math.abs(Math.floor(timezoneOffset / 60))
  const offsetMinutes = Math.abs(timezoneOffset % 60)
  const offsetSign = timezoneOffset >= 0 ? "+" : "-"

  return offsetSign + pad(offsetHours, 2) + pad(offsetMinutes, 2)
}

const getTimeZoneOffset = () => {
  const date = new Date()
  const timezoneOffset = date.getTimezoneOffset()

  return buildOffsetISO(timezoneOffset)
}

const getReadableDate = dateString => {
  const date = new Date(dateString)
  const options = {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "numeric",
    minute: "numeric",
    second: "numeric",
    // timeZoneName: 'short'
  }

  return date.toLocaleString("en-US", options)
}

const dateIsUpcoming = dateTimeString => {
  const dateTime = new Date(dateTimeString)
  const currentTime = new Date()

  return dateTime > currentTime
}

export { getTimeZoneOffset, getReadableDate, dateIsUpcoming }
