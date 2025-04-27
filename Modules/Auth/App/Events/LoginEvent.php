<?php

namespace Modules\Auth\App\Events;

use Illuminate\Queue\SerializesModels;

class LoginEvent
{
    use SerializesModels;

    public mixed $user;
    public string $type;
    public array $payload;

    public function __construct($user, $type = 'customer', $payload = [])
    {
        $this->user = $user;
        $this->type = $type;
        $this->payload = $payload;
    }
}
