<div class="modal fade" id="reschedule_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Reschedule Training Program</h4>
            </div>
            <div class="modal-body">
                <form v-on:keyup.enter="">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>New Training Date</label>
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type='text' id="training_date" data-format="yyyy-MM-dd" class="form-control"/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>New Training Time</label>
                              
                                <select v-model="training_time" class="form-control">
                                    <option v-for="(row,index) in time_options" :value="row.value">@{{ row.label}}</option>
                                </select>    
                                
                            </div>
                            
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-flat btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                <button v-on:click="saveSchedule" type="button" class="btn btn-sm btn-flat btn-primary"><i class="fa fa-check"></i> Save changes</button>
            </div>
        </div>
    </div>
</div>