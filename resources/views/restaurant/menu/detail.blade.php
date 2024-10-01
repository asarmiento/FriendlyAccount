<div class="row">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> 
        <h4 class="modal-title" id="mySmallModalLabel">Contenido del plato - {{$menuElement->name}}</h4> 
    </div>
    <div class="modal-body">
        <table class="table table-bordered table-hover TableDetail">
            <thead>
                <tr>
                    <th></th>
                    <th>Contenido</th>
                    <th>Tipo</th>
                </tr>
            </thead>
            <tbody id="productsMenu">
                @if(count($menuElement->cookedProduct) > 0)
                    <?php $i = 0; ?>
                    @foreach($menuElement->cookedProduct as $product)
                        @if($product->pivot->type == 'Base')
                        <?php $i++; ?>
                        <tr class="item">
                            <td class="text-center">
                                <button href="#" class="btn btn-danger btn-xs delete_product">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </td>
                            <td class="product" data-token="{{$product->token}}" data-type="{{$product->pivot->type}}">{{$product->name}}</td>
                            <td class="base_menu">{{$product->pivot->type}}</td>
                        </tr>
                        <input type="hidden" class="num_base" value="{{$i}}">
                        @else
                            <input type="hidden" class="aditional" data-token="{{$product->token}}" data-name="{{$product->name}}">
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td colspan="3">No se han asignados productos cocidos al plato.</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="row">
            <div id="msgEdit" class="hide">
                <div class="form-group">
                    <label for="aditional_menu">Adicionar: </label>
                    <select class="form-control" id="aditional_menu" style="display:inline-block; width: auto;">
                        
                    </select>
                    <button id="addAditional" type="button" class="btn btn-default">Agregar Adicional</button>
                </div>
            </div>
            @if(count($menuElement->cookedProduct) > 0)
                <form id="form_salon_order" method="post" class="form-inline" action="{{route('ver-salon')}}">
                    <input type="hidden" name="menu_token" id="menu_token" value="{{$menuElement->token}}">
                    <input type="hidden" name="table_token" id="table_token" value="{{$token_table}}">
                    <input type="hidden" name="modify_menu" id="modify_menu" value="0">
                    <div class="form-group">
                        <label for="qty">Cantidad:</label>
                        <input name="qty" id="qty" type="number" min="1" class="form-control" id="qty" placeholder="1" value=1>
                    </div>
                    <button type="submit" class="btn btn-success">Registrar</button>
                    <div id="msg"></div>
                </form>
            @endif
        </div>
    </div>
</div>