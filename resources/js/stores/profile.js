import axios from "axios"
import { defineStore } from "pinia"
import { ref } from "vue"

export const useProfileStore = defineStore('profile', () => {

  const profile = ref({})

  const errors = ref({
    first_name: null,
    last_name: null,
    email: null,
    phone: null,
    job: null,
    avatar: null,
  })
  const loading = ref(false)
  const errorMessage = ref(null)

  const getProfile = async () => {
    try {
      const response = await axios.get('/profile')
      profile.value = response.data.data
      return response.data.data
    } catch (e) {
      errors.value = e.response?.data?.errors
      errorMessage.value = e.response?.data?.message || 'An error has occurred while trying to fetch profile. Please try again.'
      throw e
    } finally {
      //
    }
  }

  const updateProfile = async (model) => {
    loading.value = true

    const formData = new FormData()
    formData.append('first_name', model.first_name)
    formData.append('last_name', model.last_name)
    formData.append('email', model.email)
    formData.append('phone', model.phone)
    formData.append('job', model.job)
    formData.append('avatar', model.avatar)

    try {
      const response = await axios.post('/profile', formData)
      profile.value = response.data.data
      return response.data.data
    } catch (e) {
      errors.value = e.response?.data?.errors
      errorMessage.value = e.response?.data?.message || 'An error has occurred while trying to update profile. Please try again.'
      throw e
    } finally {
      loading.value = false
    }
  }

  return {
    profile,
    loading,
    errors,
    errorMessage,
    getProfile,
    updateProfile,
  }
})
