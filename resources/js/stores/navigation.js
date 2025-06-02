import { defineStore } from "pinia"
import { computed, ref } from "vue"

export const useNavigationStore = defineStore('navigation', () => {
  const menu = ref([
    {
      name: 'Dashboard',
      icon: 'bi bi-columns-gap',
      link: '/',
      is_active: true,
      is_disabled: false,
      roles: [],
      permission: 'dashboard.access'
    }, {
      name: 'Users',
      icon: 'bi bi-people-fill',
      link: '/users',
      is_active: true,
      is_disabled: false,
      roles: [],
      permission: 'user.access'
    }
  ])
  const userPermissions = ref([])

  const filteredMenu = computed(() => {
    return menu.value.filter((item) => userPermissions.value.includes(item.permission))
  })

  const setMenu = (roles) => {
    if (roles.length > 0) {
      roles.forEach((role) => {
        role.permissions.forEach((permission) => {
          if (!userPermissions.value.includes(permission.name)) {
            userPermissions.value.push(permission.name)
          }
        })
      })
    }
  }

  return {
    menu,
    filteredMenu,
    setMenu,
  }
})
