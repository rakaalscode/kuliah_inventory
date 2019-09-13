<div class="modal fade" id="modal-form" tabindex="-1"  role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <form id="prodcutout-form" method="post" class="form-horizontal" autocomplete="off">
                    {{ csrf_field() }} {{ method_field('POST') }}
                    <div class="modal-body">
                        <div class="alert alert-danger error-productin" style="display: none"><ul></ul></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-2 offset-1 col-form-label">Product</label>
                            <div class="col-sm-8">
                                <input type="hidden" id="id" class="form-control" name="id"/>
                                <select name="product" id="product" class="form-control">
                                    <option value="">Choose Product</option>
                                    @foreach($products as $product)
                                        <option value="{{$product->id}}">{{$product->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-2 offset-1 col-form-label">Supplier</label>
                            <div class="col-sm-8">
                                <select name="supplier" id="supplier" class="form-control">
                                    <option value="">Choose Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-2 offset-1 col-form-label">Quantity</label>
                            <div class="col-sm-8">
                                <input type="number" id="quantity" class="form-control" name="quantity" placeholder="Quantity"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-2 offset-1 col-form-label">Date</label>
                            <div class="col-sm-8">
                                <input type="date" id="date" class="form-control" name="date" placeholder="Date"/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="button btn btn-primary"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
            