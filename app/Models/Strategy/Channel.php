<?php


namespace App\Models\Strategy;


use App\Models\AModel;
use App\Models\Messages\Message;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Strategy\Channel
 *
 * @property int $id_channel
 * @property string $channel
 * @property string $is_active
 * @property string|null $is_quality_control
 * @property \Illuminate\Support\Carbon $insert_datetime
 * @property \Illuminate\Support\Carbon|null $update_datetime
 * @property int $insert_user_id
 * @property int|null $update_user_id
 * @property string|null $is_communication_result
 * @property-read \Illuminate\Database\Eloquent\Collection|Message[] $messages
 * @property-read int|null $messages_count
 * @method static Builder|Channel newModelQuery()
 * @method static Builder|Channel newQuery()
 * @method static Builder|Channel query()
 * @method static Builder|Channel whereChannel( $value )
 * @method static Builder|Channel whereIdChannel( $value )
 * @method static Builder|Channel whereInsertDatetime( $value )
 * @method static Builder|Channel whereInsertUserId( $value )
 * @method static Builder|Channel whereIsActive( $value )
 * @method static Builder|Channel whereIsCommunicationResult( $value )
 * @method static Builder|Channel whereIsQualityControl( $value )
 * @method static Builder|Channel whereUpdateDatetime( $value )
 * @method static Builder|Channel whereUpdateUserId( $value )
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Strategy\QueueState[] $statuses
 * @property-read int|null $statuses_count
 */
class Channel extends AModel {

    const ZALO = 'zalo';

    public const CREATED_AT = 'insert_datetime';
    public const UPDATED_AT = 'update_datetime';

    protected $table = 'strategy_channel';
    protected $primaryKey = 'id_channel';


    public function messages() {
        return $this->hasMany( Message::class, 'id_channel', 'id_channel' );
    }

    public function statuses() {
        return $this->hasMany( QueueState::class, $this->getKeyName(), $this->getKeyName() );
    }

}
