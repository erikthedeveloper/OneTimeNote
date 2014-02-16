<?php
namespace OneTimeNote\Models;

class Note extends \Eloquent {

	protected $hidden = array('id', 'ip_address', 'url_id', 'email', 'updated_at');
    protected $fillable = array('email', 'secure_note', 'message');
	public static $rules = array();
}
