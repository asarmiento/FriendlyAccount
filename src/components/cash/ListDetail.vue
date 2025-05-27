<template>
    <div class="row wrapper">
        <div class="col-md-4 padding">
            <strong>{{ order.menu_restaurant.name }}</strong>
        </div>
        <div class="col-md-5">
            <div class="row">
                <div class="col-md-5 input" style="padding-top:.1em;">
                    <input type="number" min="1" class="form-control" width="40" v-model="order.qty">
                </div>
                <div class="col-md-7 padding">
                    <span>x</span>
                    <span>$ {{ this.order.menu_restaurant.costo | formatDecimal }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 padding">
            <p>$ {{ subtotal | formatDecimal }} <a class="pull-right" @click="remove"><i class="glyphicon glyphicon-trash"></i></a></p>
        </div>
    </div>
</template>

<script>
    import { formatDecimal } from '../../filters/filter';
    import { bus } from '../../express';
    export default {
        props: ['order'],
        data() {
            return {
                token: this.order.token
            }
        },
        computed: {
            subtotal(){
                if(this.order.price){
                    return this.order.price * this.order.quantity;
                }
                return this.order.menu_restaurant.costo * this.order.qty;
            }
        },
        filters: {
            formatDecimal: formatDecimal
        },
        methods: {
            remove(){
                this.$http.delete('/institucion/inst/salon/order/' + this.order.token, {
                        data: { "data" : JSON.stringify({"token": this.order.token}) }
                    })
                    .then((response) => {
                        if(response.data.success)
                        {
                            bus.$emit('remove-order', this.order.id);
                        }
                    });
            }
        },
        watch: {
            'order.qty': function(qty) {
                this.$http.put('/institucion/inst/cash/updateOrder/' + this._data.token,
                    {
                        'qty': qty
                }).then((response) => {
                    //console.log("obj");
                });
            }
        }
    }
</script>

<style scoped>
    .wrapper{
        padding: 0.35em;
        background-color: white;
        border-bottom: 2px solid #ccc;
    }
    .input{
        padding: 0;
    }
    .padding{
        padding-top: 7px;
    }
    a{
        cursor:  pointer;
        font-size: 12px;
        padding-top: 4px;
    }
</style>