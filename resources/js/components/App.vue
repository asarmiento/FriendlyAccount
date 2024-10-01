<template>
    <div class="col-xs-12 mar-top-1">
        <app-cash></app-cash>
    </div>
</template>

<script>
    import Cash from './cash/Cash.vue';
    export default {
        components: {
            appCash: Cash
        }
    }
</script>

<style>
    .mar-top-1{
        margin-top: 1em;
    }
</style>
