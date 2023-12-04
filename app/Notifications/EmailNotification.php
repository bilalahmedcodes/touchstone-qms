<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $audit;
    public $subject;
    public $markdown;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($audit, $type)
    {
        $this->subject = 'Default';
        $this->markdown = 'mail.blank';
        $this->audit = $audit;

        if($type == 'evaluation-rejected'){
            $this->subject = 'New Evaluation of ' . $audit->associate->name . ' for Record ID: ' . $audit->record_id;
            $this->markdown = 'mail.evaluation-rejected';
        }
        elseif($type == 'appeal-submitted'){
            $this->subject = 'Appeal Submitted by ' . $audit->appeal->user->name . ' for Record ID: ' . $audit->record_id;
            $this->markdown = 'mail.appeal-submitted';
        }
        elseif($type == 'appeal-accepted'){
            $this->subject = 'Appeal Accepted by QA Team for Record ID: ' . $audit->record_id;
            $this->markdown = 'mail.appeal-accepted';
        }
        elseif($type == 'appeal-rejected'){
            $this->subject = 'Appeal Rejected by QA Team for Record ID: ' . $audit->record_id;
            $this->markdown = 'mail.appeal-rejected';
        }
        elseif($type == 'action-submitted'){
            $this->subject = 'Action Taken by ' . $audit->appeal->user->name . ' for Record ID: ' . $audit->record_id;
            $this->markdown = 'mail.action-submitted';
        }

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->subject($this->subject)->from('lms@touchstone.com.pk', 'Touchstone LMS')->markdown($this->markdown, ['audit' => $this->audit, 'notifiable' => $notifiable]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
