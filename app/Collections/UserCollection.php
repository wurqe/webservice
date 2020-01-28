<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class UserCollection extends Collection
{
    /**
     * append users avatar.
     *
     * @return void
     */
    public function withAvatar()
    {
        $this->each->withImageUrl(null, 'avatar');
    }
}
