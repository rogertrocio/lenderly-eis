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
              <label for="exampleInputEmail1" class="form-label">Email address</label>
              <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
              <label for="exampleInputPassword1" class="form-label">Password</label>
              <input type="password" class="form-control" id="exampleInputPassword1">
            </div>
            <button type="submit" class="btn btn-primary w-100 my-4">Login</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
const login = () => {
  console.log('login')
}
</script>
