<?php

namespace Modules\SupportTicket\Entities;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\AdminManage\Entities\Admin;
use Modules\SupportTicket\Entities\SupportDepartment;
use Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Vendor\Entities\Vendor;

class SupportTicket extends Model
{
    protected $table = 'support_tickets';

    protected $fillable = [
        'title',
        'via',
        'operating_system',
        'user_agent',
        'description',
        'subject',
        'status',
        'priority',
        'user_id',
        'admin_id',
        'departments',
        'vendor_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(SupportDepartment::class, 'departments', 'id');
    }
    
    protected static function newFactory()
    {
        return \Modules\SupportTicket\Database\factories\SupportTicketFactory::new();
    }
}
