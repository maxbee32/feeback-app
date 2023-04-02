<?php

namespace App\Console\Commands;

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
        $result = DB::table('users')
        ->join('complains','users.id', '=' ,'complains.user_id')
        ->select(array(
            DB::raw("SUM(CASE
            WHEN complains.comment = 'No' THEN 1  ELSE 0 END) AS No"),
            DB::raw("SUM(CASE
            WHEN  complains.comment = 'Yes' THEN 1 ELSE 0 END) AS Yes"),
            DB::raw("COUNT(Complains.comment) As comment"),
            'email'))
        ->groupby('branch','email')
        ->get();


        foreach ($result as $key =>$result){

            $email = $result->email;


        if($result->No > $result->Yes){



        Mail::to($email)->send(new PromptEmail($result));
     }
 }

}


}
