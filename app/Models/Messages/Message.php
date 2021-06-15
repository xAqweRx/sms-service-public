<?php


namespace App\Models\Messages;


use App\Models\AModel;
use App\Models\Strategy\Template;

/**
 * App\Models\Messages\Message
 *
 * @property int|null $id_queue
 * @property int $id_message
 * @property int $id_channel
 * @property int $id_template
 * @property int $id_deal
 * @property int|null $id_target
 * @property string|null $arc_date
 * @property string $phone_no
 * @property string|null $message_text
 * @property int $id_state strategy_dim_queue_state for messenger-related channels
 * @property int|null $priority
 * @property \Illuminate\Support\Carbon $insert_datetime
 * @property \Illuminate\Support\Carbon|null $update_datetime
 * @property string|null $send_datetime
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereArcDate( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereIdChannel( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereIdDeal( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereIdMessage( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereIdQueue( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereIdState( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereIdTarget( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereIdTemplate( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereInsertDatetime( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereMessageText( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Message wherePhoneNo( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Message wherePriority( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSendDatetime( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdateDatetime( $value )
 * @mixin \Eloquent
 * @property-read Template $template
 */
class Message extends AModel {

    public const CREATED_AT = 'insert_datetime';
    public const UPDATED_AT = 'update_datetime';

    protected $table = 'strategy_messenger_list';
    protected $primaryKey = 'id_ms';

    /**
     * @var mixed
     */
    public function template() {
        $key = ( new Template() )->getKeyName();
        return $this->belongsTo( Template::class, $key, $key );
    }

}
