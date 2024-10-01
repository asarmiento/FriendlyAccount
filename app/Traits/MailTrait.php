<?php
/**
 * Created by PhpStorm.
 * User: Amwar
 * Date: 14/03/2017
 * Time: 03:31 PM
 */

namespace AccountHon\Traits;


use Illuminate\Support\Facades\Mail;

trait MailTrait
{

   public function send($messages,$data)
   {
       //echo json_encode($data); die;
       Mail::send($data['view'], compact('messages'), function($m) use($data)
       {
           if(userSchool()->email==""):
            $m->from('no-reply@sistemasamigables.com',userSchool()->name);
           else:
               $m->from(userSchool()->email,userSchool()->name);
           endif;
           //receptor
           $m->to($data['customer']->email, $data['customer']->nameComplete())->subject($data['subject']);

       });
   }
}