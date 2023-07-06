<template>
    <button :class="mode">
        <slot></slot>
    </button>
</template>

<script>
export default {
    name: 'BaseButton',
    props: ['mode']
}
</script>

<style scoped>
button {
    padding: 0.4rem 1.5rem;
    margin-left: 0.5rem;
    font-family: inherit;
    background-color: #273eed;
    border: 1px solid #273eed;
    color: white;
    cursor: pointer;
}

button:hover,
button:active {
    background-color: #270041;
    border-color: #270041;
}

.flat {
    background-color: transparent;
    color: #3a0061;
    border: none;
}

.flat:hover,
.flat:active {
    background-color: #edd2ff;
}
</style>