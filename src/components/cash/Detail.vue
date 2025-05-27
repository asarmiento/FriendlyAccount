<template>
    <div class="col-sm-5">
        <ul class="list-group">
            <li class="list-group-item">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-user"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="cliente" v-model="data.client">
                </div>
            </li>
            <li class="list-group-item">
                <span>Gracias por su compra: {{ data.client }}</span>
            </li>
        </ul>
        <div class="detail">
            <div v-if="orders.length > 0">
                <app-list-detail v-for="order in orders" :order="order"></app-list-detail>
            </div>
            <div class="blank" v-else>
                No ha agregado productos.
            </div>
        </div>
        <div class="total">
            <div class="row">
                <div class="col-sm-6">
                    <p>Método de Pago:</p>
                    <div>
                        <label class="radio-inline" v-for="paymentMethod in paymentMethods">
                            <input type="radio" :value="paymentMethod.id" v-model="data.paymentMethod">{{ paymentMethod.name }}
                        </label>
                    </div>
                    <input type="number" any="0.00" class="form-control"  placeholder="100.00" v-model="amount" min="1">
                    <button type="button" class="btn btn-success" @click="applied">Aplicar</button>
                </div>
                <div class="col-sm-6">
                    <div class="col-md-6 text-right">
                       <p>Subtotal:</p>
                    </div>
                    <div class="col-md-6">
                        <p>$ {{ subtotal | formatDecimal }} </p>
                    </div>
                    <div class="col-md-6 text-right">
                       <p>IVA:</p>
                    </div>
                    <div class="col-md-6">
                        <p>$ {{ iva | formatDecimal }}</p>
                    </div>
                    <div class="col-md-6 text-right">
                       <p><strong>Total:</strong></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>$ {{ total | formatDecimal }}</strong></p>
                    </div>
                </div>
            </div>
            <div class="row" v-if="error.validate">
                <div class="col-xs-12">
                    <div class="alert alert-danger">
                        {{ error.message }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ListDetail from './ListDetail.vue';
    import { formatDecimal } from '../../filters/filter';
    import { bus } from '../../express';
    export default {
        data() {
            return {
                orders: [],
                paymentMethods: [],
                iva_percentage: 13,
                amount: 1,
                data: {
                    total_invoice_calc: 0,
                    client: "Cliente Contado",
                    discount: 0,
                    pass: null,
                    paymentMethod: null,
                    dues: 1,
                    pay_t: 0,
                    pay: 0,
                    usd: 0,
                    missing: 0,
                    is_express: true,
                },
                error: {
                    validate: false,
                    message: ''
                }
            }
        },
        created() {
            this.$http.get('/institucion/inst/cash/listOrders')
                .then((response) => {
                    this.orders = response.data.orders;
                    this.paymentMethods = response.data.paymentMethods;
                    this.data.paymentMethod = this.paymentMethods[0].id;
                });
            bus.$on('remove-order', (order_id) => {
                this.orders.forEach( (order, index) => {
                    if(order_id === order.id)
                    {
                        this.orders.splice(index, 1);
                        return false;
                    }
                });
            });
            bus.$on("add-order", (order) => {
                this.orders.push(order);
                this.error.validate = false;
                this.error.message = "";
            });
        },
        filters: {
            formatDecimal: formatDecimal
        },
        computed: {
            subtotal() {
                let subtotal = 0;
                this.orders.forEach(product => {
                    subtotal += product.qty * product.menu_restaurant.costo;
                });
                this.data.subtotal = subtotal;
                return subtotal;
            },
            iva() {
                return this.subtotal * 0.13;
            },
            total() {
                this.data.total_invoice_calc = this.subtotal + this.iva;
                return this.subtotal + this.iva;
            }
        },
        components: {
            appListDetail: ListDetail
        },
        methods: {
            applied() {
                if(this.data.paymentMethod == 3)
                {
                    this.data.pay = Number(this.amount);
                    this.data.pay_t = 0;
                }else{
                    this.data.pay = 0;
                    this.data.pay_t = Number(this.amount);
                }
                this.$http.post('/institucion/inst/cash', this.data)
                    .then( response =>{
                        if(response.data.success === false)
                        {
                            this.error.validate = true;
                            this.error.message = response.data.errors;
                        }else{
                            $("body").append(response.data);
                            window.print();
                            this.error.validate = false;
                            this.error.message = "";
                        }
                    });
            }
        }
    }
</script>

<style scoped>
    .list-group{
        margin-bottom: 10px;
    }
    .detail{
        max-height: 54vh;
        margin-bottom: 10px;
        overflow-y: auto;
    }
    .total{
        background-color: lightblue;
        padding-top: .5em;
    }
    .btn-success{
        margin: .5em 0 !important;
    }
    .blank{
        background: white;
        padding:  1em;
    }
</style>