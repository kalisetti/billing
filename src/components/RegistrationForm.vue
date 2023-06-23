<template>
  <div class="container">
    <h2 class="mt-5">Register</h2>
    <form @submit.prevent="register" class="mt-4">
      <div class="form-group">
        <label for="username">Name:</label>
        <input type="text" id="username" v-model="username" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" v-model="email" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" v-model="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">Register</button>
    </form>
  </div>
</template>


<script>
import axios from 'axios';

export default {
  name: 'RegistrationForm',
  data() {
    return {
      username: '',
      email: '',
      password: '',
    };
  },
  methods: {
    register() {
      axios.post('/api/register.php', {
        username: this.username,
        email: this.email,
        password: this.password,
      })
      .then(response => {
        // Handle the response based on success or error
        if (response.data.message === 'User registered successfully') {
          // Registration successful
          // Redirect the user or perform additional actions
          console.log(response.data.message);
        } else {
          // Registration error
          // Display an error message or perform additional actions
          console.log(response.data.error);
          console.log(response);
        }
      })
      .catch(error => {
        // Handle the error
        // Display an error message or perform additional actions
        console.log('ERRRRR:',error);
      });
    },
  },
};
</script>
