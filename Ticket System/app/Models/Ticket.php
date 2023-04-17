<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $table = 'tickets';
    public $timestamps = false;
    protected $fillable = [
        'summary', 'description','ticket_type_id','status_id'
        ,'created_by','created_date'
        ,'resolved_by','resolved_date'
        ,'deleted_by','deleted_date'
        ,'severity','priority'
    ];
}
