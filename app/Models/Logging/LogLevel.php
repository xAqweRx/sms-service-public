<?php


namespace App\Models\Logging;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Logging\LogLevel
 *
 * @property int $id_level
 * @property string $level
 * @property int $priority
 * @property string $insert_datetime
 * @property int $insert_user_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\LogLevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\LogLevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\LogLevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\LogLevel whereIdLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\LogLevel whereInsertDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\LogLevel whereInsertUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\LogLevel whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\LogLevel wherePriority($value)
 * @mixin \Eloquent
 */
class LogLevel extends Model {

    protected $table = "level_type";
    protected $connection = 'logging';

    protected $fillable = [
        "id_level",
        "level",
        "priority",
        "insert_datetime",
        "insert_user_id"
    ];

}
