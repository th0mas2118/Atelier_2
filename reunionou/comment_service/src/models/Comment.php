<?php

namespace reunionou\src\models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Messages extends \Illuminate\Database\Eloquent\Model
{

    protected $table      = 'comment';  /* le nom de la table */
    protected $primaryKey = 'id';

    public  $incrementing = false;      //pour primarykey, on annule l'auto_increment
    public $timestamps = true;

    protected $fillable = array(
        'id', 'content', 'media', 'created_at', 'updated_at'
    );

}
