<?php
   
namespace App\Console\Commands;
   
use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class TimingCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TimingCron:cron';
    
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
      $appointment = Appointment::where('date',date('Y-m-d'))->where('date',date('Y-m-d'))->get();
  
      if(count($appointment)>0 && date('h:i') == '00:01'){
        foreach($appointment as $item){
            $message = trans('notifications.next_appointment');
            $userId = $item->patient_id;
            $title = 'Today is your appointment';
            $appointmentID = $item->id;
           
            $userName = !empty(User::getNameById($userId)) ? User::getNameById($userId) :'User';
            $appointment = Appointment::where('id',$item->id)->first();
            $arr1 = array('{user}','{app_id}','{doctorname}');
            $arr2 = array($userName->name,$item->id,$appointment->doctor->name ?? '');
            $message = str_replace($arr1, $arr2, $message);
              Notification::create([
                    'user_id'          => $userId,
                    'appointment_id'   => $item->id,
                    'title'            => $title,
                    'message'          => $message,
                ]);
                
        }
      }
    }
}