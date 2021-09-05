<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Model;

/**
 * @property string $unionid
 * @property string $token
 * @property string $session_key
 */
class WxSession extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wx_session';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['unionid', 'token', 'session_key'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
}
