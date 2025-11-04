<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasEncryptedAttributes;

class SecureData extends Model
{
    use HasFactory, HasEncryptedAttributes;

    protected $table = 'secure_data';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'credit_card',
        'social_security_number',
        'medical_info',
        'financial_info',
        'is_active',
        'security_level'
    ];

    protected $encryptedAttributes = [
        'name',
        'email',
        'phone',
        'address',
        'credit_card',
        'social_security_number',
        'medical_info',
        'financial_info'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Scope for active records
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific security level
     */
    public function scopeSecurityLevel($query, $level)
    {
        return $query->where('security_level', $level);
    }

    /**
     * Get security level options
     */
    public static function getSecurityLevels()
    {
        return [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'critical' => 'Critical'
        ];
    }
}
