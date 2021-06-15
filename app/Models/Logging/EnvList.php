<?php


namespace App\Models\Logging;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Logging\EnvList
 *
 * @property int $id_env
 * @property string|null $ip_server
 * @property string|null $name_server
 * @property string $insert_datetime
 * @property int $insert_user_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\EnvList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\EnvList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\EnvList query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\EnvList whereIdEnv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\EnvList whereInsertDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\EnvList whereInsertUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\EnvList whereIpServer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\EnvList whereNameServer($value)
 * @mixin \Eloquent
 */
class EnvList extends Model {
    protected $table = "environment_list";
    protected $connection = 'logging';

    protected $fillable = [
        "id_env",
        "ip_server",
        "name_server",
        "insert_datetime",
        "insert_user_id"
    ];

}
