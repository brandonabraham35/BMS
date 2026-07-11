<?php

namespace App\Domains\Organization\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PolicyChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(public array $context) {}
}
