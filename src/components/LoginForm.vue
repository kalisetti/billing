<template>
    <div class="container">
      <h2 class="mt-5">Login</h2>
      <form @submit.prevent="login" class="mt-4">
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" v-model="email" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" id="password" v-model="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
      </form>
    </div>
  </template>
  
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'LoginForm',
    data() {
      return {
        email: '',
        password: '',
      };
    },
    methods: {
      login() {
        axios.post('/api/login.php', {
          email: this.email,
          password: this.password,
        })
        .then(response => {
          // Handle the response based on success or error
          if (response.data.message === 'Login successful') {
            // Login successful
            // Redirect the user or perform additional actions
            console.log('Login successful');
          } else {
            // Login error
            // Display an error message or perform additional actions
            console.log('Login error');
          }
        })
        .catch(error => {
          // Handle the error
          // Display an error message or perform additional actions
          console.log('ERRRR: ', error);
        });
      },
    },
  };
  </script>
  