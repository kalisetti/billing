<template>
    <div>
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Desk</a>
        <button
          class="navbar-toggler"
          type="button"
          data-toggle="collapse"
          data-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <router-link class="nav-link" to="/desk">Home</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/desk/customers"
                >Customers</router-link
              >
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/desk/invoices"
                >Invoices</router-link
              >
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/desk/payments"
                >Payments</router-link
              >
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/desk/settings"
                >Settings</router-link
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
  nav {
    margin-bottom: 20px;
  }
  </style>
  