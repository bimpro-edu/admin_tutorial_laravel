<?php

namespace ThaoHR;

use Mail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use ThaoHR\Events\User\RequestedPasswordResetEmail;
use ThaoHR\Presenters\Traits\Presentable;
use ThaoHR\Presenters\UserPresenter;
use ThaoHR\Services\Auth\Api\TokenFactory;
use ThaoHR\Services\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatable;
use ThaoHR\Services\Auth\TwoFactor\Contracts\Authenticatable as TwoFactorAuthenticatableContract;
use ThaoHR\Support\Authorization\AuthorizationUserTrait;
use ThaoHR\Support\CanImpersonateUsers;
use ThaoHR\Support\Enum\UserStatus;
use Illuminate\Support\Collection;

class User extends Authenticatable implements TwoFactorAuthenticatableContract, JWTSubject, MustVerifyEmail
{
    use TwoFactorAuthenticatable,
        CanResetPassword,
        Presentable,
        AuthorizationUserTrait,
        Notifiable,
        CanImpersonateUsers;

    protected $presenter = UserPresenter::class;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $dates = ['last_login', 'birthday'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'username', 'first_name', 'last_name', 'phone', 'avatar',
        'address', 'country_id', 'birthday', 'last_login', 'confirmation_token', 'status',
        'remember_token', 'role_id', 'email_verified_at','lat','long', 'fcm_token', 'leader_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Always encrypt password when it is updated.
     *
     * @param $value
     * @return string
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function setBirthdayAttribute($value)
    {
        $this->attributes['birthday'] = trim($value) ?: null;
    }

    public function gravatar()
    {
        $hash = hash('md5', strtolower(trim($this->attributes['email'])));

        return sprintf("https://www.gravatar.com/avatar/%s?size=150", $hash);
    }

    public function isUnconfirmed()
    {
        return $this->status == UserStatus::UNCONFIRMED;
    }

    public function isActive()
    {
        return $this->status == UserStatus::ACTIVE;
    }

    public function isBanned()
    {
        return $this->status == UserStatus::BANNED;
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->id;
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return ['jti' => app(TokenFactory::class)->forUser($this)->id];
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        Mail::to($this)->send(new \ThaoHR\Mail\ResetPassword($token));

        event(new RequestedPasswordResetEmail($this));
    }
    
    public function fullName()
    {
        return $this->first_name .' '.$this->last_name;
    }
    
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }
    
    public function children ()
    {
        return $this->hasMany(User::class, 'leader_id');
    }
    
    public function getAllChildren ()
    {
        $users = new Collection();
        
        foreach ($this->children as $user) {
            $users->push($user);
            $users = $users->merge($user->getAllChildren());
        }
        
        return $users;
    }
}
