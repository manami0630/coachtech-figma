<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Evaluation;

class EvaluationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $evaluation;

    public function __construct(Order $order, Evaluation $evaluation)
    {
        $this->order = $order;
        $this->evaluation = $evaluation;
    }

    public function build()
    {
        return $this->subject('取引が完了しました')->markdown('emails.evaluation');
    }
}