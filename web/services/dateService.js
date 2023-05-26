const pad = (value, length) => ((value.toString().length < length) ? pad(`0${value}`, length) : value)

const buildOffsetISO = (timezoneOffset) => {
  const offsetHours = Math.abs(Math.floor(timezoneOffset / 60))
  const offsetMinutes = Math.abs(timezoneOffset % 60)
  const offsetSign = (timezoneOffset >= 0 ? '+' : '-')

  return offsetSign + pad(offsetHours, 2) + pad(offsetMinutes, 2)
}

const getTimeZoneOffset = () => {
  const date = new Date()
  const timezoneOffset = date.getTimezoneOffset()

  return buildOffsetISO(timezoneOffset)
}

export {
  getTimeZoneOffset,
}
