<div class="modal fade" id="dealer_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@{{ form_title }}</h4>
            </div>
            <div class="modal-body">
                <form v-on:keyup.enter="storeItem">
                    <input type="hidden" v-model="form.dealer_id">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="dealer">Dealer</label>
                            <input type="text" class="form-control" id="dealer" v-model="form.dealer">
    
                            <span v-if="errors.dealer" class="text-danger">
                                @{{ errors.dealer[0] }}
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="branch">Branch</label>
                            <input type="text" class="form-control" id="branch" v-model="form.branch">
                            <span v-if="errors.branch" class="text-danger">
                                @{{ errors.branch[0] }}
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="branch">Email</label>
                            <input type="text" class="form-control" id="branch" v-model="form.email">
                            <span v-if="errors.email" class="text-danger">
                                @{{ errors.email[0] }}
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-flat btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                <button type="button" class="btn btn-sm btn-flat btn-primary" v-on:click="storeItem"><i class="fa fa-check"></i> Save changes</button>
            </div>
        </div>
    </div>
</div>