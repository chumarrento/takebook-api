<?php


namespace App\Jobs;


use App\Entities\User\User;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;

class SendMailJob extends Job
{
	/**
	 * @var User
	 */
	private $user;
	/**
	 * @var string
	 */
	private $token;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, string $token)
	{
		$this->user = $user;
		$this->token = $token;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		Mail::to($this->user)
			->send(new ResetPasswordMail(['user' => $this->user, 'token' => $this->token]));
	}
}
