<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class ServiceCollection extends Collection
{
    /**
     * append service attachments.
     *
     * @return void
     */
    public function withAttachments()
    {
        $this->each->withImageUrl(null, 'attachments');
    }

    public function withMetas()
    {
        $this->each->withMetas();
    }
}
