<template>
  <div class="container">
    <div class="row justify-content-center align-items-center vh-100">
      <div class="card" style="width: 400px;">
        <div class="card-body">
          <div class="d-flex flex-column justify-content-center text-center my-4">
            <span class="fs-5">Lenderly</span>
            <span class="fs-4 fw-medium">Employee Information System</span>
          </div>
          <form @submit.prevent="login">
            <div class="mb-3">
              <BaseInput
                label="Email Address"
                id="email"
                v-model="model.email"
                :error="store.errors.email?.[0] || null"
                @keydown="store.errors.email = null" />
            </div>

            <div class="mb-3">
              <BaseInput
                label="Password"
                id="password"
                type="password"
                v-model="model.password"
                :error="store.errors.password?.[0] || null"
                @keydown="store.errors.password = null" />
            </div>

            <BaseButton
              type="submit"
              class="w-100 my-4"
              label="Login"
              variant="primary"
              :loading="store.loading"
              :disabled="store.loading" />
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'
import { useAuthStore } from '../stores/auth'
import BaseButton from '../components/base/BaseButton.vue'
import BaseInput from '../components/base/BaseInput.vue'

const router = useRouter()
const store = useAuthStore()
const model = ref({
  email: null,
  password: null,
})

const login = async () => {
  try {
    await store.login(model.value)

    toast.success('You have successfully logged in.')

    window.location.href = '/'
  } catch (e) {
    toast.error(store.errorMessage)
  }
}
</script>
