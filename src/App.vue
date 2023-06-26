<template>
  <div>
    <!-- <h1>User Login and Registration</h1>
    <router-link to="/login">Login</router-link> |
    <router-link to="/register">Register</router-link>
    <router-view></router-view> -->
    <header>
      <router-view name="header"></router-view>
    </header>
    <main>
      <router-view></router-view>
    </main>
    <footer>
      <router-view name="footer"></router-view>
    </footer>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "App",
  created() {
    console.log('App.vue: created step1...');
    this.checkAuthentication();
    console.log('App.vue: created step2...');
  },
  methods: {
    checkAuthentication() {
      console.log('App.vue: checkAuthentication');
      axios.get("/api/checkAuth.php")
        .then((response) => {
          if (response.data.loggedIn) {
            // User is already logged in, redirect to the desk page
            console.log('response.data.loggedIn', response.data.loggedIn);
            this.$router.push('/desk');
          } else {
            this.$router.push('/login');
          }
        })
        .catch((error) => {
          console.log("Error checking authentication status:", error);
        });
    },
    logout() {
      axios.get("/api/logout.php")
        .then(() => {
          // Logout successful, redirect to the login page
          this.$router.push('/login');
        })
        .catch(error => {
          console.log('Error logging out: ', error);
        });
    }
  },
  watch: {
    $route(to) {
      // Check authentication whenever the route changes
      console.log('App.vue: watch', to);
      this.checkAuthentication();
    },
  },
  provide() {
    return {
      logout: this.logout
    }
  }
};
</script>

<style scoped>
/* h1 {
  text-align: center;
} */
</style>
