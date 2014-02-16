<?php
namespace OneTimeNote\Models;

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
class Note extends \Eloquent {

	protected $hidden = array('id', 'ip_address', 'url_id', 'email', 'updated_at');
    protected $fillable = array('email', 'secure_note', 'message');
	public static $rules = array();
}
