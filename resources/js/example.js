import Vue from 'vue';
import $ from 'jquery';
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
Vue.component('table-bufete', {
    // TODO: pasar variables al component
    props: ['sales'],
    data() {
        return {
            'dataSales': []
        }
    },
    created() {
        this.dataSales = this.sales;
    },
    // TODO: Funcione el botón +
    // TODO: Validar los datos antes de enviar el ajax
    // TODO: Enviar el ajax
    // TODO: Actualizar las variables del componente
    // TODO: Eliminar un fila del componente
    template: `
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th></th>
                    <th class="text-center">Descripción</th>
                    <th class="text-center">Monto</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <new-sale></new-sale>
                <detail v-for="(sale, index) in dataSales"  :description="sale.description" :amount="sale.amount" :token="sale.token" :index="index"></detail>
            </tbody>
        </table>
    `,
});

Vue.component('newSale', {
    template: `
        <tr>
            <td></td>
            <td style="padding-left: 5px">
                <textarea v-model="description" rows="5" cols="60" maxlength="500"></textarea>
            </td>
            <td style="padding-left: 15px">
                <input v-model="amount" type="number" id="amountSaleOfTheFirm" class="form-control">
            </td>
            <td style="padding-left: 15px">
                <button @click.prevent="addSale" class="btn btn-success"><span class="fa fa-plus"></span></button>
            </td>
        </tr>
    `,
    data() {
        return {
            description: "",
            amount: 0,
            token:"",
        }
    },
    methods: {
        addSale: function(e) {

            // TODO: Validar campos
            // TODO: Hacer el ajax
            // TODO: Actualizar props sales de table-bufete
            let sale = {'description': this.description, 'amount':this.amount};
            let parent = this.$parent;
            $.ajax({
                url: 'save',
                method: 'POST',
                data: sale,
                dataType: 'json',
                error: function(e){
                    // TODO: el parent se usa porque no se puede aplicar this dentro del ajax (borrar esto)
                    parent.dataSales.push(sale);
                },
                success: function (data) {
                    if(data.success){
                        console.log(data);
                        parent.dataSales.push(sale);
                    }
                }
            });



        },


    }
});



Vue.component('detail',{
    props: ["description", "amount", "token", "index"],
    template: `
        <tr>
            <td><a @click.prevent="deleteSale(index)" class="btn btn-danger"><span class="fa fa-remove"></span></a></td>
            <td style="padding-left: 5px">{{ description }}</td>
            <td style="padding-left: 15px">{{ amount  }}</td>
            <td style="padding-left: 15px"></td>
        </tr>
    `,
    methods: {
        deleteSale: function (index) {
            let sale = {'description': this.description, 'amount':this.amount,'token':this.token};
            let parent = this.$parent;
            $.ajax({
                url: 'delete/'+this.token,
                method: 'DELETE',
                dataType: 'json',
                error: function(e){
                    // TODO: el parent se usa porque no se puede aplicar this dentro del ajax (borrar esto)
                   // parent.dataSales.remove(sale);
                },
                success: function (data) {
                    if(data.success){
                       parent.dataSales.splice(index,1);
                    }
                }
            });
        }
    }
});

new Vue({
    el: '#example'
});