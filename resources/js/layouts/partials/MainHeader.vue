<template>
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="/">Employee Information System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li
              class="nav-item"
              style="cursor: pointer;"
              @click="router.push({ name: 'Profile'})">
            <span class="nav-link">Profile</span>
            </li>
            <li
              class="nav-item"
              style="cursor: pointer;"
              @click="showLogoutConfirmDialog">
              <span class="nav-link">Logout</span>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <ConfirmDialog
    ref="logoutConfirmDialogRef"
    id="sampleModal"
    title="Confirmation"
    message="Are you sure you want to log out?"
    confirmLabel="Logout"
    confirmVariant="danger"
    :loading="store.loading"
    @confirm="logout"
    @cancel="closeLogoutConfirmDialog" />
</template>
<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'
import { useAuthStore } from '../../stores/auth'
import ConfirmDialog from '../../components/dialogs/ConfirmDialog.vue'

const router = useRouter()
const store = useAuthStore()

const logoutConfirmDialogRef = ref(null)

const showLogoutConfirmDialog = () => { logoutConfirmDialogRef.value.show() }

const closeLogoutConfirmDialog = () => { logoutConfirmDialogRef.value.hide() }

const logout = async () => {
  try {
    await store.logout()

    toast.success('You have been logged out successfully.')
    closeLogoutConfirmDialog()

    window.location.href = '/login'
  } catch (e) {
    toast.error(store.errorMessage)
  }
}
</script>
