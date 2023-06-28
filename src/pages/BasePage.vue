<template>
    <div>
        <header>
            <page-header></page-header>
            <nav-bar></nav-bar>
        </header>
        <main>
            <router-view id="main"></router-view>
        </main>
        <footer>
            <page-footer></page-footer>
        </footer>
    </div>
</template>

<script>
import PageHeader from './PageHeader.vue';
import PageFooter from './PageFooter.vue';
import NavBar from '../components/nav/NavBar.vue';

export default {
    name: "BasePage",
    components: { PageHeader, PageFooter, NavBar },
};
</script>

<style scoped>
main {
    min-height: 500px;
}
</style>
