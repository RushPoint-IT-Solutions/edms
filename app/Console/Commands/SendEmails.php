<?php

namespace App\Console\Commands;
use App\Permit;
use App\User;
use Illuminate\Console\Command;

use App\Notifications\ForRenewal;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:due';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //
        $request = $this->email_notif();

        return $this->info($request);
    }
    public function email_notif()
    {
        info("START EMAIL");
        $users = User::where('status',null)->get();
        foreach($users as $user)
        {
            $permits = Permit::with('company', 'department')->get();
            
            if($user->role == "Document Control Officer")
            { 
                $permits = Permit::with('company', 'department')->whereIn('department_id',($user->dco)->pluck('department_id')->toArray())->get();
            }
            if(($user->role == "Department Head"))
            {
                $permits = Permit::with('company', 'department')->whereIn('department_id',($user->permits)->pluck('department_id')->toArray())->get();
            }
            if(($user->role == "User"))
            {
                $permits = Permit::with('company', 'department')->whereIn('department_id',($user->accountable_persons)->pluck('department_id')->toArray())->get();
            }
            if(($user->role == "Documents and Records Controller"))
            {
                $permits = Permit::with('company', 'department')->whereIn('department_id',($user->accountable_persons)->pluck('department_id')->toArray())->get();
            }

            $countPermit = count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d'))))));
            $countOverdue = count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d')));
            if($countOverdue > 0)
            {
                $user->notify(new ForRenewal($countPermit,$countOverdue));
            }
        }
        info("END EMAIL");
        return "success";
    }
}
