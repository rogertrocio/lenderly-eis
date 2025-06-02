import axios from "axios"
import { defineStore } from "pinia"
import { ref } from "vue"


export const useCommonStore = defineStore('common', () => {
  const roles = ref([])
  const errorMessage = ref(null)

  const getRoles = async () => {
    try {
      const response = await axios.get('/common/roles')
      roles.value = response.data.data.map((role) => {
        return { label: role.name, value: role.id }
      })
    } catch (e) {
      errorMessage.value = e.response?.data?.message || 'An error has occurred while trying to fetch roles. Please try again.'
    }
  }

  return {
    roles,
    getRoles,
  }
})
