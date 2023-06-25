<template>
  <div>
    <h1>User Login and Registration</h1>
    <router-link to="/login">Login</router-link> |
    <router-link to="/register">Register</router-link>
    <router-view></router-view>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'App',
  created() {
    this.checkAuthentication();
  },
  methods: {
    checkAuthentication() {
      axios.get('/api/checkAuth.php')
        .then(response => {
          if (response.data.loggedIn) {
            // User is already logged in, redirect to the desk page
            this.$router.push('/desk');
          }
        })
        .catch(error => {
          console.log('Error checking authentication status:', error);
        })
    }
  },
  watch: {
    $route(to) {
      // Check authentication whenever the route changes
      this.checkAuthentication();
    }
  }
};
</script>

<style>
h1 {
  text-align: center;
}
</style>
