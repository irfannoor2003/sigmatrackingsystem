<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Mail\CustomerNewsletter;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendMonthlyNewsletter extends Command
{
    protected $signature = 'newsletter:send';
    protected $description = 'Send monthly newsletter to all customers';

    public function handle()
    {
        $oneMonthAgo = Carbon::now()->subMonth();

        // Get customers who have email and either never received newsletter or it's been a month
        $customers = Customer::whereNotNull('email')
            ->where(function ($query) use ($oneMonthAgo) {
                $query->whereNull('last_newsletter_sent_at')
                      ->orWhere('last_newsletter_sent_at', '<=', $oneMonthAgo);
            })
            ->get();

        if ($customers->isEmpty()) {
            $this->info('No customers need the newsletter at this time.');
            return;
        }

        foreach ($customers as $customer) {
            // Extra safety check
            if (empty($customer->email)) {
                $this->info("Skipping {$customer->name} - no email set.");
                continue;
            }

            Mail::to($customer->email)->send(new CustomerNewsletter($customer));

            // Update last newsletter sent timestamp
            $customer->update(['last_newsletter_sent_at' => now()]);

            $this->info("Newsletter sent to {$customer->email}");
        }

        $this->info('Monthly newsletter job completed!');
    }
}
