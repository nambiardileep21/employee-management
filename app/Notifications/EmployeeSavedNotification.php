<?php

namespace App\Notifications;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmployeeSavedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Employee $employee,
        public string $action = 'created'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Employee Record {$this->action}: {$this->employee->full_name}")
            ->greeting("Hello,")
            ->line("An employee record has been {$this->action} in the system.")
            ->line("**Name:** {$this->employee->full_name}")
            ->line("**Email:** {$this->employee->email}")
            ->line("**Designated Location:** {$this->employee->city->name}, {$this->employee->state->name}, {$this->employee->country->name}")
            ->action('View Employee List', route('employees.index'))
            ->line('Thank you for using the system.');
    }
}