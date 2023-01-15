<?php
// TODO нотификейшн, в данный момент не используется, поэтому закоментирован.
//namespace App\Notifications;
//
//use Illuminate\Bus\Queueable;
//use Illuminate\Contracts\Queue\ShouldQueue;
//use Illuminate\Notifications\Messages\MailMessage;
//use Illuminate\Notifications\Notification;
//
//class SendOtpCode extends Notification implements ShouldQueue
//{
//    use Queueable;
//
//    private $text;
//
//    /**
//     * Create a new notification instance.
//     *
//     * @return void
//     */
//    public function __construct($text)
//    {
//        $this->text = $text;
//    }
//
//    /**
//     * Get the notification's delivery channels.
//     *
//     * @param mixed $notifiable
//     * @return array
//     */
//    public function via($notifiable): array
//    {
//        return ['mail'];
//    }
//
//    /**
//     * Get the mail representation of the notification.
//     *
//     * @param mixed $notifiable
//     * @return MailMessage
//     */
//    public function toMail($notifiable): MailMessage
//    {
//        return (new MailMessage())
//            ->greeting('Код подтверждения')
//            ->line($this->text);
//    }
//
//    /**
//     * Get the array representation of the notification.
//     *
//     * @param mixed $notifiable
//     * @return array
//     */
//    public function toArray($notifiable): array
//    {
//        return [
//            //
//        ];
//    }
//}
