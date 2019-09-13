<div class="modal fade" id="modal-form" tabindex="-1"  role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <form id="prodcut-form" method="post" class="form-horizontal" autocomplete="off">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-body">
                    <div class="alert alert-danger error-product" style="display: none"><ul></ul></div>
                    <div class="form-group row">
                        <div class="col-sm-1"></div>
                        <label class="col-sm-2 offset-1 col-form-label">Name</label>
                        <div class="col-sm-8">
                            <input type="hidden" id="id" class="form-control" name="id"/>
                            <input type="text" id="name" class="form-control" name="name" placeholder="Product name"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-1"></div>
                        <label class="col-sm-2 offset-1 col-form-label">Category</label>
                        <div class="col-sm-8">
                            <select name="category" id="category" class="form-control">
                                <option value="">Choose Category</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-1"></div>
                        <label class="col-sm-2 offset-1 col-form-label">Image</label>
                        <div class="col-sm-8">
                            <input type="file" id="image" class="form-control" name="image"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-1"></div>
                        <label class="col-sm-2 offset-1 col-form-label">Price</label>
                        <div class="col-sm-8">
                            <input type="number" id="price" class="form-control" name="price" placeholder="Rp."/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-1"></div>
                        <label class="col-sm-2 offset-1 col-form-label">Quantity</label>
                        <div class="col-sm-8">
                            <input type="number" id="quantity" class="form-control" name="quantity" placeholder="Quantity"/>
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
    