<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Schema;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'no_hp',
        'username',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Tambahkan ini
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected static $is_add = ['no_hp','username','password','role','email'];
    protected static $is_edit = ['no_hp','username','password','role','email'];
    protected static $is_delete = ['no_hp','username','password','role','email'];
    protected static $is_filter = ['role'];
    protected static $is_search = ['no_hp','username','email'];

    protected static $rules = [
        'no_hp' => 'required|regex:/^08[0-9]{8,11}$/',
        'username' => 'required|string|max:15|unique:users',
        'password' => 'required|string',
        'email' => 'required|email|unique:users',
        'role' => 'required'
    ];

    public static function getAllowedFields($type){
        return match($type){
            'add' => self::$is_add,
            'edit' => self::$is_edit,
            'delete' => self::$is_delete,
            'filter' => self::$is_filter,
            'search' => self::$is_search,
            default => [],
        };
    }

    public static function getValidationRules($type)
    {
        $allowedFields = self::getAllowedFields($type);
        $rules = [];

        foreach ($allowedFields as $field) {
            if (isset(self::$rules[$field])) {
                $rules[$field] = self::$rules[$field];
            }
        }
        return $rules;
    }

    /**
     * Get all of the setting for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function setting(): HasMany
    {
        return $this->hasMany(SettingPresensi::class, 'id_user', 'id');
    }

    /**
     * Get all of the presensi for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function presensi(): HasMany
    {
        return $this->hasMany(Presensi::class, 'id_user', 'id');
    }

    public function settingPresensis(): BelongsToMany
    {
        return $this->belongsToMany(SettingPresensi::class, 'setting_presensi_user', 'user_id', 'setting_presensi_id');
    }

    // public function getRelations()
    // {
    //     return [
    //         'setting' => function ($query) {
    //             $query->select('id', 'id_user','hari','waktu'); 
    //         },
    //         'presensi' => function ($query) {
    //             $query->select('id', 'id_presensi', 'id_setting','jam_masuk','jam_keluar','latitude','longitude','status');
    //         }
    //     ];
    // }

    public function getRelations(){
        return [
            'setting' => function ($query) {
                $columns = Schema::getColumnListing('setting_presensis');
                $columns = array_diff($columns, ['created_at', 'updated_at']); 
                $query->select($columns);
            },
            'presensi' => function ($query) {
                $columns = Schema::getColumnListing('presensis');
                $columns = array_diff($columns, ['created_at', 'updated_at']);
                $query->select($columns);
            },
        ];
    }
}