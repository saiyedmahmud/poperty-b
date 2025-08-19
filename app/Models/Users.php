<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Users extends Model
{
    use HasFactory, HasUuids;

    //create user model
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $hidden = ['refreshToken', 'password', 'isLogin'];
    protected $fillable = [
        'firstName',
        'lastName',
        'username',
        'email',
        'refreshToken',
        'salary',
        'employeeId',
        'phone',
        'street',
        'city',
        'state',
        'zipCode',
        'bloodGroup',
        'image',
        'joinDate',
        'leaveDate',
        'password',
        'roleId',
        'designationId',
        'employmentStatusId',
        'departmentId',
        'shiftId',
        'salaryMode',
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

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'roleId');
    }
}
