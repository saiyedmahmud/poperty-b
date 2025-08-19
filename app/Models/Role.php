<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory, HasUuids;

    //create role model
    protected $table = 'role';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $fillable = [
        'name',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = self::generateUniqueKey(10);
        });
    }

    /**
     * @throws Exception
     */
    protected static function generateUniqueKey($length): string
    {
        $characters = "ABCDEFGHOPQRSTUYZ0123456IJKLMN789VWX";
        $key = "";

        for ($i = 0; $i < $length; $i++) {
            $key .= $characters[random_int(0, strlen($characters) - 1)];
        }
        // Ensure the key is unique
        while (static::where('id', $key)->exists()) {
            $key .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $key;
    }

    public function rolePermission(): HasMany
    {
        return $this->hasMany(RolePermission::class, 'roleId');
    }
}
