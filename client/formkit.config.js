import { generateClasses } from '@formkit/themes'

export default {
  config: {
    classes: generateClasses({
      global: {
        outer: 'mb-5 flex flex-col',
        label: 'mb-1 block text-base text-[#07074D]',
        input: 'w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-4 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md'
      },
    }),
  },
}
