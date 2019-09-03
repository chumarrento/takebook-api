<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 15/03/19
 * Time: 14:48
 */

namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $user = $this->data['user']['first_name'];
        $book = $this->data['book'];
        $address = 'no-reply@takebook.com';
        $subject = 'Novo livro cadastrado para análise';
        $name = 'Admin Takebook';
        return $this->view('mails.notifications.check-book')
            ->from($address, $name)
            ->subject($subject)
            ->with([ 'name' => $user, 'book' => $book ]);
    }
}