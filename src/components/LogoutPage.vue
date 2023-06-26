<template>
    <div></div>
</template>

<script>

export default {
    beforeMount() {
        console.log('LogoutPage','beforeMount',this);
        this.logout();
    },
    inject: ['logout'],
};
</script>
