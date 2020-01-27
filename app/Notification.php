<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
  protected $fillable = ['data', 'read_at', 'type'];

}
