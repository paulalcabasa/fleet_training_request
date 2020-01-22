<div class="modal fade" id="approver_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@{{ formTitle }}</h4>
            </div>
            <div class="modal-body">
                <form v-on:keyup.enter="storeItem">
                    <input type="hidden" v-model="form.person_id">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">Type</label>
                            <select name="person_type" id="person_type" class="form-control" v-model="form.person_type">
                                <option value="trainer">Trainer</option>
                                <option value="approver">Approver</option>
                                <option value="dealer_sales">Dealer Sales</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" v-model="form.first_name">
                            <span v-if="errors.first_name" class="text-danger">
                                @{{ errors.first_name[0] }}
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="middle_name">Middle Name</label>
                            <input type="text" class="form-control" id="middle_name" v-model="form.middle_name">
    
                            <span v-if="errors.middle_name" class="text-danger">
                                @{{ errors.middle_name[0] }}
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="last_name" v-model="form.last_name">
    
                            <span v-if="errors.last_name" class="text-danger">
                                @{{ errors.last_name[0] }}
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" v-model="form.email">
    
                            <span v-if="errors.email" class="text-danger">
                                @{{ errors.email[0] }}
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="position">Position</label>
                            <input type="text" class="form-control" id="position" v-model="form.position">
    
                            <span v-if="errors.position" class="text-danger">
                                @{{ errors.position[0] }}
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-flat btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                <button v-on:click="saveItem" type="button" class="btn btn-sm btn-flat btn-primary"><i class="fa fa-check"></i> Save changes</button>
            </div>
        </div>
    </div>
</div>