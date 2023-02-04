<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
        protected $fillable = ['created_by', 'last_updated_by', 'driver', 'host', 'port', 'user_name', 'password', 'encryption','status'];

}