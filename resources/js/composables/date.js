import dayjs from 'dayjs'
import customParseFormat from 'dayjs/plugin/customParseFormat'
import relativeTime from 'dayjs/plugin/relativeTime'

dayjs.extend(relativeTime)
dayjs.extend(customParseFormat);

export function useDate () {
  const format = (value, format = 'YYYY-MM-DD HH:mm:ss') => {
    return dayjs(value).format(format)
  }

  const humanize = ($value) => {
    return dayjs().to(dayjs($value))
  }

  return {
    format,
    humanize,
  }
}
