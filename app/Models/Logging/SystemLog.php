<?php


namespace App\Models\Logging;



use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Logging\SystemLog
 *
 * @property int $id
 * @property string|null $event_date
 * @property string $event_datetime
 * @property int|null $id_user
 * @property int|null $id_lead
 * @property int|null $id_deal
 * @property string|null $ip_user
 * @property int $id_database
 * @property int $id_level
 * @property int $id_env
 * @property string|null $log_source
 * @property string|null $log_text
 * @property string|null $log_params
 * @property string|null $log_trace
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\SystemLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\SystemLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\SystemLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\SystemLog whereEventDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\SystemLog whereEventDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\SystemLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\SystemLog whereIdDatabase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\SystemLog whereIdDeal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\SystemLog whereIdEnv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\SystemLog whereIdLead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\SystemLog whereIdLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\SystemLog whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\SystemLog whereIpUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\SystemLog whereLogParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\SystemLog whereLogSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\SystemLog whereLogText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\SystemLog whereLogTrace($value)
 * @mixin \Eloquent
 */
class SystemLog extends Model {


    const EXCEPTION_FIELDS = ["password", "user_password", "pswd"];


    public $timestamps = false;

    protected $connection = 'logging';
    protected $table = 'system_log';
    protected $fillable = [
        "id",
        "event_date",
        "event_datetime",
        "id_user",
        "id_lead",
        "id_deal",
        "ip_user",
        "id_database",
        "id_level",
        "id_env",
        "log_source",
        "log_text",
        "log_params",
        "log_trace",
    ];

}
