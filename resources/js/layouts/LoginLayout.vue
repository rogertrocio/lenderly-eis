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
              <label for="email" class="form-label">Email address</label>
              <input
                class="form-control" :class="{'is-invalid' : store.errors.email?.[0] }"
                id="email"
                aria-describedby="emailHelp"
                v-model="model.email"
                @keydown="store.errors.email = null">
                <div v-if="store.errors.email?.[0] !== null" class="invalid-feedback">
                  {{ store.errors.email?.[0] }}
                </div>
            </div>
            <div class="mb-3">
              <label for="exampleInputPassword1" class="form-label">Password</label>
              <input
                type="password"
                class="form-control"
                id="exampleInputPassword1"
                v-model="model.password">
            </div>
            <button type="submit" class="btn btn-primary w-100 my-4">Login</button>
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
    router.go('/')
  } catch (e) {
    toast.error(store.errorMessage)
  }
}
</script>
