<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'email', 
        'phone', 
        'role', 
        'photo', 
        'bio', 
        'is_active',
        'order',
        // NOVOS CAMPOS LP
        'domain',
        'lp_slug',
        'lp_settings',
        'has_lp',
        // Redes Sociais (que já existiam)
        'facebook',
        'instagram',
        'linkedin',
        'tiktok',
        'whatsapp'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'has_lp' => 'boolean',      // <--- Garante que vem true/false
        'lp_settings' => 'array',   // <--- Mágica do Laravel: converte JSON para Array automaticamente
    ];

    // Helper para pegar a foto ou uma default (já tínhamos algo assim ou parecido)
    public function getImageUrlAttribute()
    {
        return $this->photo 
            ? asset('storage/' . $this->photo) 
            : asset('img/default-avatar.png'); // Ajuste o caminho se necessário
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }
}