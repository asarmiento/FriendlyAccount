<template>
    <div class="row">
        <app-menu></app-menu>
        <app-detail></app-detail>
    </div>
</template>

<script>
    import Menu from './Menu.vue';
    import Detail from './Detail.vue';
    export default {
        components: {
            appMenu: Menu,
            appDetail: Detail
        }
    }
</script>

<style>

</style>