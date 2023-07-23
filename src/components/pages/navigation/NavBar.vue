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
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Subscription
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li>
                  <router-link class="dropdown-item" to="/desk/List/subscription-plan">
                    Subscription Plan
                  </router-link>
                </li>
                <li>
                  <router-link class="dropdown-item" to="/desk/List/subscription">
                    Subscription
                  </router-link>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <router-link class="dropdown-item" to="/desk/List/subscription-settings">
                    Settings
                  </router-link>
                </li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Invoices
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li>
                  <router-link class="dropdown-item" to="/desk/List/invoice-entry">
                    Invoice Entry
                  </router-link>
                </li>
                <li>
                  <router-link class="dropdown-item" to="/desk/List/invoice">
                    Invoices
                  </router-link>
                </li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Payments
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li>
                  <router-link class="dropdown-item" to="/desk/List/payment">
                    Payment Entry
                  </router-link>
                </li>
              </ul>
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
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto"> <!-- "ms-auto" class aligns items to the right -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Settings
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="settingsDropdown"> <!-- "dropdown-menu-end" class aligns dropdown to the right -->
                <li>
                  <a class="dropdown-item" href="#">
                    <i class="bi bi-person me-1"></i>
                    My Profile
                  </a>
                </li>
                <li>
                  <router-link class="dropdown-item" to="/logout">
                    <i class="bi bi-box-arrow-right me-1"></i>
                    Logout
                  </router-link>
                </li>
              </ul>
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
  