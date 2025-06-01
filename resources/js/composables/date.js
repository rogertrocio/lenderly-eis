import dayjs from 'dayjs'
import customParseFormat from 'dayjs/plugin/customParseFormat'

dayjs.extend(customParseFormat);

export function useDate () {
  const format = (value, format = 'YYYY-MM-DD HH:mm:ss') => {
    return dayjs(value).format(format)
  }

  return {
    format,
  }
}
