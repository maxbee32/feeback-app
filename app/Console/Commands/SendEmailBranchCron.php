<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Complain;
use App\Mail\PromptEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendEmailBranchCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendemailbranch:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $date1 = Carbon::today();
        $result = DB::table('users')
        ->join('complains','users.id', '=' ,'complains.user_id')
        ->where(DB::raw('DATE(complains.created_at)',''),[$date1])
        ->select(array(
            DB::raw("SUM(CASE
            WHEN complains.comment = 'No' THEN 1  ELSE 0 END) AS No"),
            DB::raw("SUM(CASE
            WHEN  complains.comment = 'Yes' THEN 1 ELSE 0 END) AS Yes"),
            DB::raw("COUNT(Complains.comment) As comment"),
            DB::raw("DATE(complains.created_at) As date"),
            'email', 'branch'))
        ->groupby('branch','email', 'date')
        ->get();

        // customerfeedbackapp@izweghana.com

         foreach ($result as $result){
            if($result->No > $result->Yes){

            $email = $result->email;



        // if($result->pluck('No') > $result->pluck('Yes')){


       Mail::to($email)->cc("customerfeedbackapp@izweghana.com")->send(new PromptEmail($result));
      }
 }

}


}
