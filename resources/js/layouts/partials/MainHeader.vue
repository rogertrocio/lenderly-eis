<template>
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Employee Information System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="#">Profile</a></li>
            <li
              class="nav-item"
              style="cursor: pointer;"
              @click="logout">
              <span class="nav-link">Logout</span>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
</template>
<script setup>
import { useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'
import { useAuthStore } from '../../stores/auth'

const router = useRouter()
const store = useAuthStore()

const logout = async () => {
  try {
    await store.logout()
    toast.success('You have been logged out successfully.')
    router.go('/login')
  } catch (e) {
    toast.error(store.errorMessage)
  }
}
</script>
