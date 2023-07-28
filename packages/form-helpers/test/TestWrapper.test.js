import { shallowMount } from '@vue/test-utils'
import { vi, describe, it, expect } from 'vitest'
import TestWrapper from '../src/runtime/components/TestWrapper.vue'
import AlertWrapper from '../src/runtime/components/Alerts/AlertWrapper.vue'
import DynamicForm from '../src/runtime/components/DynamicForm.vue'

const $apiService = {
	submissions: {
		store() {
			return {
				data: null,
				status: null,
				message: null,
			}
		},
	},
}

vi.mock('$apiService', () => ({
	submissions: {
		store: () => ({}),
	},
}))

describe('Dynamic Form and Alert Message', async () => {
	it('renders success message on 201 response', async () => {
		const mockSuccess = {
			status: 201,
			data: {
				status: 'success',
				message: 'Your message has been successfully submitted.',
			},
		}

		const wrapper = shallowMount(TestWrapper)

		await wrapper.vm.handleSubmit(
			{
				first_name: 'Colin',
				last_name: 'Rabyniuk',
				email: 'colinxr@getMaxListeners.com',
				phone: '2894893617',
				idea: 'tk tk tk',
			},
			mockSuccess
		)

		vi.spyOn($apiService.submissions, 'store').mockResolvedValue(mockSuccess)

		expect(wrapper.vm.showFormAlert).toBe(true)
		expect(wrapper.vm.formStatus).toEqual('success')
		expect(wrapper.vm.alertMessage).toEqual(
			'Your message has been successfully submitted.'
		)

		const alert = wrapper.findComponent(AlertWrapper)
		expect(alert.exists()).toBe(true)
		expect(alert.props().msg).toEqual(
			'Your message has been successfully submitted.'
		)
		expect(alert.props().status).toEqual('success')
	})

	it('renders Error message on 500 response', async () => {
		const mockError = {
			status: 500,
			data: {
				status: 'error',
				message: 'no column exists on the database',
			},
		}

		const wrapper = shallowMount(TestWrapper)

		await wrapper.vm.handleSubmit(
			{
				first_name: 'Colin',
				last_name: 'Rabyniuk',
				email: 'colinxr@getMaxListeners.com',
				phone: '2894893617',
				idea: 'tk tk tk',
			},
			mockError
		)

		vi.spyOn($apiService.submissions, 'store').mockResolvedValue(mockError)

		expect(wrapper.vm.showFormAlert).toBe(true)
		expect(wrapper.vm.formStatus).toEqual('error')
		expect(wrapper.vm.alertMessage).toEqual('Something went wrong')

		const alert = wrapper.findComponent(AlertWrapper)
		expect(alert.exists()).toBe(true)
		expect(alert.props().msg).toEqual('Something went wrong')
		expect(alert.props().status).toEqual('error')
	})

	it('renders Validation Errors on 422 response', async () => {
		const mockError = {
			status: 422,
			data: {
				message: 'The given data was invalid.',
				errors: {
					first_name: ['The first name field is required.'],
					email: ['The email must be a valid email address.'],
				},
			},
		}

		const wrapper = shallowMount(TestWrapper)

		await wrapper.vm.handleSubmit(
			{
				first_name: 'Colin',
				last_name: 'Rabyniuk',
				email: 'colinxr@getMaxListeners.com',
				phone: '2894893617',
				idea: 'tk tk tk',
			},
			mockError
		)

		expect(wrapper.vm.showFormAlert).toBe(true)
		expect(wrapper.vm.formStatus).toEqual('error')
		expect(wrapper.vm.alertMessage).toEqual('The given data was invalid.')

		console.log('wrapper: ' + wrapper.vm.validationErrs)

		expect(wrapper.vm.validationErrs).toBeTruthy()

		expect(wrapper.vm.validationErrs.email[0]).toEqual(
			'The email must be a valid email address.'
		)

		await wrapper.vm.$nextTick()

		const alert = wrapper.findComponent(AlertWrapper)
		expect(alert.exists()).toBe(true)
		expect(alert.props().msg).toEqual('The given data was invalid.')
		expect(alert.props().status).toEqual('error')
	})
})
