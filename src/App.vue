<template>
  <div>
    <base-page :isUserLoggedIn="this.isUserLoggedIn"></base-page>
  </div>
</template>

<script>
import axios from "axios";
import BasePage from './pages/BasePage.vue';

export default {
  name: "App",
  components: { BasePage },
  data() {
    return {
      isUserLoggedIn: false
    }
  },
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
            this.isUserLoggedIn = true;
            if (this.$route.path === "/login") {
              this.$router.push('/desk');
            }
          } else {
            this.isUserLoggedIn = false;
            if (this.$route.path !== "/register") {
              this.$router.push('/login');
            }
          }
        })
        .catch((error) => {
          this.isUserLoggedIn = false;
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
      logout: this.logout,
      // isUserLoggedIn: this.isUserLoggedIn,
    }
  }
};
</script>

<style>
#form-body {
    min-height: 500px;
}

.form-group {
    margin: 0.5rem 0;
}

.pull-right {
    float: right;
}

.awesomplete {
    width: 100%;
}
</style>