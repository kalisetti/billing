<template>
    <div class="navbar-custom">
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <!-- <a class="navbar-brand" href="#">Desk</a> -->
        <router-link class="navbar-brand" to="/desk">
          <i class="bi bi-house-fill"></i>
        </router-link>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <router-link class="nav-link" to="/desk/List/customers"
                >Customers</router-link
              >
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/desk/List/invoices"
                >Invoices</router-link
              >
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/desk/List/payments"
                >Payments</router-link
              >
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/desk/List/settings"
                >Settings</router-link
              >
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/desk/List/users"
                >Users</router-link
              >
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </template>
  
  <script>
  export default {
    name: "NavBar",
  };
  </script>
  
<style scoped>
/* nav {
  margin-bottom: 20px;
} */
.navbar-custom {
  border-bottom: 1px solid #4682b452;
}

.navbar-custom nav {
  padding: 0 1rem;
}

.navbar-custom .navbar-brand {
  font-size: 1.95rem;
  opacity: 0.7;
}
</style>
  