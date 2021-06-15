<?php


namespace App\Models\Logging;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Logging\DatabaseList
 *
 * @property int $id_database
 * @property string $database_name
 * @property string $insert_datetime
 * @property int $insert_user_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\DatabaseList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\DatabaseList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\DatabaseList query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\DatabaseList whereDatabaseName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\DatabaseList whereIdDatabase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\DatabaseList whereInsertDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging\DatabaseList whereInsertUserId($value)
 * @mixin \Eloquent
 */
class DatabaseList extends Model {
    protected $table = "database_list";
    protected $connection = 'logging';

    protected $fillable = [
        "id_database",
        "database_name",
        "insert_datetime",
        "insert_user_id"
    ];

}
