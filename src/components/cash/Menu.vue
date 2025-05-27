<template>
    <div class="col-sm-7">
        <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="Producto" v-model="filterProduct" @keyup="filter"/>
            <i class="glyphicon glyphicon-search form-control-feedback"></i>
        </div>
        <ul class="list-group">
            <app-list-menu v-for="menu in listMenu" :menu="menu"></app-list-menu>
        </ul>
    </div>
</template>

<script>
    import ListMenu from './ListMenu.vue';
    export default {
        data(){
            return {
                filterProduct: '',
                listMenu: []
            }
        },
        created(){
            this.$http.post('/institucion/inst/cash/listMenu', {
                filterProduct: this.filterProduct
            }).then((response) => {
                this.listMenu = response.data;
            });
        },
        components: {
            appListMenu: ListMenu
        },
        methods: {
            filter(){
                this.$http.post('/institucion/inst/cash/listMenu', {
                    filterProduct: this.filterProduct
                }).then((response) => {
                    this.listMenu = response.data;
                });
            }
        }
    }
</script>

<style scoped>
    .col-sm-7{
        min-height: 85vh;
        max-height: 85vh;
        background: white;
        padding: 1em;
        border-radius: .25em;
    }
    ul{
        height: 70vh;
        overflow-y: auto;
    }
</style>