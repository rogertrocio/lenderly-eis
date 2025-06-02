import axios from "axios"
import { defineStore } from "pinia"
import { reactive, ref } from "vue"

export const useUserStore = defineStore('user', () => {
  const users = ref([])
  const user = ref({})
  const loading = ref(false)
  const pagination = ref({
    current_page: 1,
    per_page: 10,
    total: 0,
    links: [],
  })
  const filter = reactive({
    search: '',
    role: null,
  })
  const errors = ref({
    first_name: null,
    last_name: null,
    email: null,
    phone: null,
    job: null,
    avatar: null,
  })
  const errorMessage = ref(null)

  const getUsers = async (page = 1) => {
    loading.value = true

    const params = { page }

    if (filter.search !== null && filter.search !== '') params['filter[search]'] = filter.search

    try {
      const response = await axios.get('/users', { params })
      users.value = response.data.data

      pagination.value.current_page = response.data.meta.current_page
      pagination.value.per_page = response.data.meta.per_page
      pagination.value.total = response.data.meta.total
      pagination.value.links = response.data.meta.links
    } catch (e) {
      errorMessage.value = e.response?.data?.message || 'An error has occurred while trying to fetching users. Please try again.'
    } finally {
      loading.value = false
    }
  }

  const getUser = async (id) => {
    loading.value = true

    try {
      const response = await axios.get(`/users/${id}`)
      user.value = response.data.data
      return response.data.data
    } catch (e) {
      errors.value = e.response?.data?.errors
      errorMessage.value = e.response?.data?.message || 'An error has occurred while trying to fetch user. Please try again.'
      throw e
    } finally {
      loading.value = false
    }
  }

  const updateUser = async (model, id) => {
    loading.value = true

    const formData = new FormData()
    formData.append('first_name', model.first_name)
    formData.append('last_name', model.last_name)
    formData.append('email', model.email)
    formData.append('phone', model.phone)
    formData.append('job', model.job)
    formData.append('avatar', model.avatar)

    try {
      const response = await axios.post(`/users/${id}`, formData)
      user.value = response.data.data
      return response.data.data
    } catch (e) {
      errors.value = e.response?.data?.errors
      errorMessage.value = e.response?.data?.message || 'An error has occurred while trying to update user. Please try again.'
      throw e
    } finally {
      loading.value = false
    }
  }

  const deleteUser = async (id) => {
    loading.value = true

    try {
      const response = await axios.delete(`/users/${id}`)
      return response
    } catch (e) {
      errorMessage.value = e.response?.data?.message || 'An error has occurred while trying to delete user. Please try again.'
      throw e
    } finally {
      loading.value = false
    }
  }

  const exportReport = async (type = 'csv') => {
    loading.value = true

    if (type == 'csv') {
      try {
        const response = await axios.get('/users/export/csv', { params: { 'filter[search]': filter.search }, responseType: 'arraybuffer' })
        const newBlob = new Blob([response.data], { type: 'text/csv' })
        const link = document.createElement('a')
        link.href = window.URL.createObjectURL(newBlob)
        link.download = 'users.csv'
        link.click()
      } catch (e) {
          errorMessage.value = e.response?.data?.message || 'An error has occurred while trying to export CSV. Please try again.'
      } finally {
        loading.value = false
      }
    }

    if (type == 'pdf') {
      try {
        const response = await axios.get('/users/export/pdf', { params: { 'filter[search]': filter.search }, responseType: 'blob' })
        const newBlob = new Blob([response.data])
        const link = document.createElement('a')
        link.href = window.URL.createObjectURL(newBlob)
        link.download = 'users.pdf'
        link.click()
      } catch (e) {
          errorMessage.value = e.response?.data?.message || 'An error has occurred while trying to export PDF. Please try again.'
      } finally {
        loading.value = false
      }
    }
  }

  return {
    users,
    user,
    loading,
    pagination,
    filter,
    errors,
    errorMessage,
    getUsers,
    getUser,
    updateUser,
    deleteUser,
    exportReport,
  }
})
