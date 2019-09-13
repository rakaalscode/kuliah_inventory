<div class="modal fade" id="modal-form" tabindex="-1"  role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <form id="category-form" method="post" class="form-horizontal" autocomplete="off">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-body">
                    <div class="alert alert-danger error-category" style="display: none"><ul></ul></div>
                    <div class="form-group row">
                        <div class="col-sm-1"></div>
                        <label class="col-sm-2 offset-1 col-form-label">Name</label>
                        <div class="col-sm-8">
                            <input type="hidden" id="id" class="form-control" name="id"/>
                            <input type="text" id="name" class="form-control" name="name"/>
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
