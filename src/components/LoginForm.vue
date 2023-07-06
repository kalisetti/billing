<template>
  <base-card>
    <div class="container">
      <h2 class="mt-5">Login</h2>
      <form @submit.prevent="login" class="mt-4">
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" v-model="email" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" id="password" v-model="password" class="form-control" autocomplete="off" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
      </form>

      <base-dialog v-if="msgprint" title="Something" @close="confirmError">
        <template #default>
          <p>This is some message</p>
          <p>And more details</p>
        </template>
        <template #actions>
          <base-button @click="confirmError">Okay</base-button>
        </template>
      </base-dialog>
    </div>
  </base-card>
</template>
  
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'LoginForm',
    data() {
      return {
        email: '',
        password: '',
        msgprint: false,
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
            console.log(response.data.message);
            this.$router.push('/desk');
          } else {
            // Login error
            // Display an error message or perform additional actions
            console.log(response.data.error);
            console.log(response);
            this.msgprint = true;
          }
        })
        .catch(error => {
          // Handle the error
          // Display an error message or perform additional actions
          console.log('ERRRR: ', error);
          this.msgprint = true;
        });
      },
      confirmError() {
        this.msgprint = false;
      }
    },
  };
  </script>
  