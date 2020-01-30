<div class="modal fade" id="training_program_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@{{ form_title }}</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" v-model="form.training_program_id">
                <div class="form-row">
                    <div class="form-group">
                        <label for="program_title">Program Title</label>
                        <input type="text" class="form-control" id="program_title" v-model="form.program_title">

                        <span v-if="errors.program_title" class="text-danger">
                            @{{ errors.program_title[0] }}
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                       <!--  <textarea class="form-control" name="description" id="description" cols="20" rows="1" v-model="form.description"></textarea>
 -->
                        <ckeditor :editor="editor" v-model="form.description" :config="editorConfig"></ckeditor>
                        <span v-if="errors.description" class="text-danger">
                            @{{ errors.description[0] }}
                        </span>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="feature">Topics</label>
                            <input type="text" class="form-control" id="features" v-model="form.feature" v-on:keyup.enter.prevent="storeFeature">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div v-if="features.length > 0" class="card mt-4">
                                <ul class="list-group list-group-flush">
                                    <li v-for="(item, index) in features" 
                                    v-bind:key="index"
                                    class="list-group-item">
                                        <i class="fa fa-caret-right mr-2"></i>
                                        @{{ item.feature }}
                                        <button v-on:click="removeFeature(item.program_feature_id, index)"
                                        class="btn btn-xs btn-danger pull-right">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-flat btn-default" data-dismiss="modal"><i class="fa fa-times"></i> CLOSE</button>
                <button type="button" class="btn btn-sm btn-flat btn-primary" v-on:click="storeItem"><i class="fa fa-check"></i> SAVE CHANGES</button>
            </div>
        </div>
    </div>
</div>