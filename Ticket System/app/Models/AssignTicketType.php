<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignTicketType extends Model
{
    use HasFactory;
    protected $table = 'assign_ticket_types';
    public $timestamps = false;
    protected $fillable = [
        'ticket_type_id','role_id','can_create','can_resolve'
    ];
}

