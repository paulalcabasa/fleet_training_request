<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Reports extends Model
{
    public function getRequestSummary($params){
        $sql = "SELECT tr.training_request_id,
                        tr.status,
                        tr.company_name,
                        tr.training_address,
                        dl.dealer,
                        tr.office_address,
                        tr.contact_person,
                        tr.position,
                        tr.contact_number,
                        tr.email,
                        tr.remarks,
                        DATE_FORMAT(tr.training_date,'%M %d, %Y') training_date,
                        (SELECT GROUP_CONCAT(tp.program_title SEPARATOR ' / ')
                            FROM training_request_programs trp INNER JOIN training_programs tp
                                ON tp.training_program_id = trp.training_program_id
                            WHERE trp.training_request_id = tr.training_request_id
                        ) training_programs,
                        (SELECT GROUP_CONCAT(CONCAT(p.first_name, ' ', p.last_name) SEPARATOR ' / ')
                            FROM designated_trainors dt INNER JOIN persons p
                                        ON p.person_id = dt.person_id
                            WHERE dt.training_request_id = tr.training_request_id
                        ) assigned_trainer,
                        (SELECT GROUP_CONCAT(cm.model SEPARATOR ' / ')
                            FROM customer_models cm
                            WHERE cm.training_request_id = tr.training_request_id
                        ) unit_models,
                        (SELECT GROUP_CONCAT(cp.participant SEPARATOR ' / ')
                            FROM customer_participants cp
                            WHERE cp.training_request_id = tr.training_request_id
                        ) participants,
                        (SELECT SUM(quantity)
                            FROM customer_participants cp
                            WHERE cp.training_request_id = tr.training_request_id
                        ) no_of_participants
                FROM training_requests tr
                    LEFT JOIN dealer_details dd
                        ON dd.training_request_id = tr.training_request_id
                    LEFT JOIN dealers dl
                        ON dl.dealer_id = dd.dealer_id
                WHERE 1 = 1
                    AND tr.training_date BETWEEN :start_date AND :end_date";
        $query = DB::select($sql,$params);
        return $query;
    }
}
