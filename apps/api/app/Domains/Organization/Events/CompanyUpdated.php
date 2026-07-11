<?php

namespace App\Domains\Organization\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CompanyUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(public string $companyId) {}
}
