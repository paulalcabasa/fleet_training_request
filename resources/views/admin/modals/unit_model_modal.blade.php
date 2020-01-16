<div class="modal fade" id="unit_model_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@{{ form_title }}</h4>
            </div>
            <div class="modal-body">
                <form v-on:keyup.enter="storeItem">
                    <input type="hidden" v-model="form.unit_model_id">
                    <div class="form-row">
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <img :src="image2" class="img-responsive" height="200" width="180">
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                        <div class="form-group">
                            <label for="model_name">Model Name</label>
                            <input type="text" class="form-control" id="model_name" v-model="form.model_name">
    
                            <span v-if="errors.model_name" class="text-danger">
                                @{{ errors.model_name[0] }}
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" id="description" v-model="form.description">
    
                            <span v-if="errors.description" class="text-danger">
                                @{{ errors.description[0] }}
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="description">Sequence</label>
                            <input type="text" class="form-control" id="sequence_no" v-model="form.sequence_no">
                            <span v-if="errors.sequence_no" class="text-danger">
                                @{{ errors.sequence_no[0] }}
                            </span>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label v-if="isEdit" for="image">Replace Image</label>
                                <label v-else for="image">Image</label>
                                <input type="file" id="image" v-on:change="onImageChange">

                                <span v-if="errors.image" class="text-danger">
                                    @{{ errors.image[0] }}
                                </span>
                            </div>
                            <div class="form-group col-md-8" v-if="image">
                                <img :src="image" class="img-responsive" height="160" width="150">
                            </div>
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