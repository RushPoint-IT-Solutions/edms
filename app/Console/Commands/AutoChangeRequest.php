<?php

namespace App\Console\Commands;

use App\ChangeRequest;
use App\DepartmentApprover;
use App\PreAssessment;
use App\RequestApprover;
use Illuminate\Console\Command;

class AutoChangeRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:autocr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatic Change Request';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $preAssessment = PreAssessment::where('status', 'Pending')->get();

        foreach($preAssessment as $pa)
        {
            $target_date = date('Y-m-d', strtotime("+10 days", strtotime($pa->created_at)));
            $date_today = date('Y-m-d');

            if ($target_date <= $date_today)
            {
                $pre_assessment = PreAssessment::where('id', $pa->id)->first();
                $pre_assessment->status = "Approved";
                $pre_assessment->save();

                $changeRequests = ChangeRequest::where('pre_assessment_id', $pa->id)->get();
                
                foreach ($changeRequests as $changeRequest) {
                    $departmentApprovers = DepartmentApprover::where('department_id', $pa->department_id)
                                                            ->orderBy('level', 'asc')
                                                            ->get();
                    
                    foreach ($departmentApprovers as $approver) {
                        $requestApprover = new RequestApprover;
                        $requestApprover->change_request_id = $changeRequest->id;
                        $requestApprover->user_id = $approver->user_id;
    
                        if ($approver->level == 1) {
                            $requestApprover->status = 'Pending';
                            $requestApprover->start_date = date('Y-m-d');
                        } else {
                            $requestApprover->status = 'Waiting';
                        }
                        $requestApprover->level = $approver->level;
                        $requestApprover->save();
                    }
                }
            }

        }
    }
}
