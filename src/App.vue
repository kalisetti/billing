<template>
  <div>
    <h1>User Login and Registration</h1>
    <router-link to="/login">Login</router-link> |
    <router-link to="/register">Register</router-link>
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  name: 'App',
};
</script>

<style>
h1 {
  text-align: center;
}
</style>
