<?php
namespace OneTimeNote\Models;
use Way\Database\Model;

/**
 * An Eloquent Model: 'OneTimeNote\Models\Note'
 *
 * @property integer $id
 * @property string $secure_note
 * @property string $email
 * @property string $ip_address
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $url_id
 */
class Note extends Model {

	protected $hidden = array('id', 'ip_address', 'url_id', 'email', 'created_at', 'updated_at');
    protected $fillable = array('email', 'secure_note');
    protected static $rules = array(
        'secure_note' => 'required|min:1',
        'email' => 'email'
    );
}
