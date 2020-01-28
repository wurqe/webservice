<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class ServiceCollection extends Collection
{
    /**
     * append users avatar.
     *
     * @return void
     */
    public function withAttachments()
    {
        $this->each->withImageUrl(null, 'attachments');
    }
}
