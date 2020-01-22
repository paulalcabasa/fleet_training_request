<style>
    .row_style {
        text-align:left;
        vertical-align:middle;
 
    }

    .row_style2 {
        text-align:center;
        vertical-align:middle;
     
    }

    .header_row_style {
        text-align:center;
        vertical-align:middle;
        font-weight:bold;
         background-color:#808080;
        color:#fff;
        font-family:Arial;
    }

    .header_row {
        background-color:#808080;
        color:#fff;
        font-family:Arial;
      
    }
</style>

<table border="1" cellpadding="5" cellspacing="10">
    <tdead> 
        <tr>
            <td rowspan="2" width="15" class="header_row_style">NO.</td>
            <td rowspan="2" width="35" class="header_row_style">COMPANY NAME</td>
            <td rowspan="2" width="20" class="header_row_style">TRAINING DATE</td>
            <td rowspan="2" width="35" class="header_row_style">TRAINING PROGRAM</td>
            <td width="25" class="header_row_style">ASSIGNED TRAINER /</td>
            <td rowspan="2"  width="50" class="header_row_style">TRAINING VENUE</td>
            <td rowspan="2" width="25" class="header_row_style">UNIT PURCHASED</td>
            <td width="25" class="header_row_style">REPEATED TRAINING</td>
            <td rowspan="2" width="25" class="header_row_style">NO. OF PARTICIPANTS</td>
            <td rowspan="2" width="25" class="header_row_style">DESIGNATION OF PARTICIPANTS</td>
            <td rowspan="2" width="25" class="header_row_style">SELLING DEALER</td>
            <td rowspan="2" width="25" class="header_row_style">COMPANY ADDRESS</td>
            <td colspan="3" width="25" class="header_row_style">CONTACT PERSON</td>
            <td rowspan="2" width="25" class="header_row_style">EMAIL ADDRESS</td>
            <td rowspan="2" width="25" class="header_row_style">ADDITIONAL REQUEST</td>
            <td rowspan="2" width="25" class="header_row_style">STATUS</td>
            
        </tr>
        <tr >
            <td></td> <!-- No -->
            <td></td> <!-- company name -->
            <td></td> <!-- training date -->
            <td></td> <!-- training program -->
            <td class="header_row_style">FLEET SERVICE TECHNICIAN REPRESENTATIVE</td>
            <td></td> <!-- venue -->
            <td></td> <!-- unit -->
            <td class="header_row_style">YES/NO</td>
            <td></td> <!-- no -->
            <td></td> <!-- designatiom -->
            <td></td> <!-- sellin -->
            <td></td> <!-- address -->
            <td class="header_row_style">NAME</td>
            <td class="header_row_style">POSITION</td>
            <td class="header_row_style">CONTACT NUMBER</td>
            <td></td> <!-- email -->
            <td></td> <!-- additional -->
            <td></td> <!-- status -->
        </tr>
    </tdead>
    <tbody>

    <?php 
        $ctr = 1;
        foreach($data as $row) { 
    ?>
        <tr>
            <td class="row_style2"><?php echo $ctr; ?></td>
            <td class="row_style"><?php echo $row->company_name; ?></td>
            <td class="row_style"><?php echo $row->training_date; ?></td>
            <td class="row_style"><?php echo $row->training_programs; ?></td>
            <td class="row_style"><?php echo $row->assigned_trainer; ?></td>
            <td class="row_style"><?php echo $row->training_address; ?></td>
            <td class="row_style"><?php echo $row->unit_models; ?></td>
            <td class="row_style"></td>
            <td class="row_style2"><?php echo $row->no_of_participants; ?></td>
            <td class="row_style"><?php echo $row->participants; ?></td>
            <td class="row_style"><?php echo $row->dealer; ?></td>
            <td class="row_style"><?php echo $row->office_address; ?></td>
            <td class="row_style"><?php echo $row->contact_person; ?></td>
            <td class="row_style"><?php echo $row->position; ?></td>
            <td class="row_style"><?php echo $row->contact_number; ?></td>
            <td class="row_style"><?php echo $row->email; ?></td>
            <td class="row_style"><?php echo $row->remarks; ?></td>
            <td class="row_style"><?php echo $row->status; ?></td>
        </tr>
    <?php 
            $ctr++;
        }    
    ?> 
    </tbody>
</table>
