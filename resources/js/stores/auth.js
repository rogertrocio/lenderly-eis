import axios from "axios"
import { defineStore } from "pinia"
import { ref } from "vue"

export const useAuthStore = defineStore('auth', () => {
  const errors = ref({
    email: null,
    password: null,
  })
  const loading = ref(false)
  const errorMessage = ref(null)

  const login = async (model) => {
    loading.value = true

    try {
      const response = await axios.post('/authenticate', model)
      return response.data.data
    } catch (e) {
      errors.value = e.response?.data?.errors
      errorMessage.value = e.response?.data?.message || 'An error has occurred while trying to login. Please try again.'
      throw e
    } finally {
      loading.value = false
    }
  }

  const logout = async () => {
    loading.value = true

    try {
      const response = await axios.post('/logout')
      return response
    } catch (e) {
      errorMessage.value = e.response?.data?.message || 'An error has occurred while trying to logout. Please try again.'
      throw e
    } finally {
      loading.value = false
    }
  }

  const checkIn = async () => {
    loading.value = true

    try {
      const response = await axios.post('/check-in')
      return response
    } catch (e) {
      errorMessage.value = e.response?.data?.message || 'An error has occurred while trying to check-in. Please try again.'
      throw e
    } finally {
      loading.value = false
    }
  }

  const checkOut = async () => {
    loading.value = true

    try {
      const response = await axios.post('/check-out')
      return response
    } catch (e) {
      errorMessage.value = e.response?.data?.message || 'An error has occurred while trying to check-out. Please try again.'
      throw e
    } finally {
      loading.value = false
    }
  }

  return {
    loading,
    errors,
    errorMessage,
    login,
    logout,
    checkIn,
    checkOut,
  }
})
