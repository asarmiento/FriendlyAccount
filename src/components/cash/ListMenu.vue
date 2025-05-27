<template>
    <li class="list-group-item">
        <div class="row">
            <div class="col-sm-6">
                <span>{{ menu.name }}</span>
            </div>
            <div class="col-sm-3">
                <span>{{ (menu.price || menu.costo) | filterPrice }}</span>
            </div>
            <div class="col-sm-3 text-right">
                <a @click="add"><i class="glyphicon glyphicon-ok"></i></a>
            </div>
        </div>
    </li>
</template>

<script>
    import { bus } from '../../express.js';
    export default {
        props: ['menu'],
        methods: {
            add(){
                let data = {};
                if(this.menu.costo){
                    data.type = 'menu';
                }else{
                    data.type = 'cooked';
                }
                data.token = this.menu.token;
                this.$http.post('/institucion/inst/salon-order', data)
                    .then((response) => {
                        if(response.data.success)
                        {
                            let order = response.data.message;
                            order.menu_restaurant = this.menu;
                            bus.$emit('add-order', order);
                        }
                    });
            }
        },
        filters: {
            filterPrice(value) {
                var tot = parseFloat(value) * 1.13;
                return tot.toFixed(0);
            }
        }
    }
</script>

<style scoped>
    a{
        cursor: pointer;
    }
    .col-sm-4:first-child{
        padding-left: 0;
    }
</style>