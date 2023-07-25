<template>
    <div>
        <header>
            <page-header></page-header>
            <nav-bar v-if="isUserLoggedIn"></nav-bar>
        </header>
        <main>
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <router-view></router-view>
                </div>
                <div class="col-md-1"></div>
            </div>
        </main>
        <footer>
            <page-footer></page-footer>
        </footer>
    </div>
</template>

<script>
import PageHeader from './PageHeader.vue';
import PageFooter from './PageFooter.vue';
import NavBar from './navigation/NavBar.vue';

export default {
    name: "BasePage",
    components: { PageHeader, PageFooter, NavBar },
    props: ['isUserLoggedIn']
    // inject: ['isUserLoggedIn']
};
</script>

<style scoped>
main {
    min-height: 500px;
    font-size: 0.95rem;
    padding: 1rem;
}
</style>

<style>
.error-border {
  border-color: red !important;
  background-color: #fffdf4;
}
.required {
  color: red;
}
</style>