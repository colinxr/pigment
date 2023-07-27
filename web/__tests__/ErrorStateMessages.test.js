import { mount } from '@vue/test-utils'
import SubmissionForm from '../pages/[username].vue'
import AlertWrapper from '#imports'

describe('[usename].vue', () => {
	it('renders message on 201 response', async () => {
		const wrapper = mount(SubmissionForm)

		// mock 201 response
		wrapper.vm.onSubmit({
			status: 201,
			data: {
				status: 'success',
				message: 'Your message has been successfully submitted.',
			},
		})

		expect(wrapper.vm.showFormAlert).toBe(true)
		expect(wrapper.vm.formStatus).toBe('success')
		expect(wrapper.vm.message).toBe('Error message')
		expect(wrapper.vm.alertMessage).toBe(
			'Your message has been successfully submitted.'
		)

		// assert MessageComponent rendered
		const alertWrapper = wrapper.findComponent(AlertWrapper)

		expect(alertWrapper.exists()).toBe(true)

		// assert MessageComponent received correct props
		expect(alertWrapper.props().msg).toBe(
			'Your message has been successfully submitted.'
		)
		expect(alertWrapper.props().status).toBe('success')
	})
})
