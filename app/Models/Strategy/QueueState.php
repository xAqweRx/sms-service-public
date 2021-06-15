<?php


namespace App\Models\Strategy;


use App\Models\AModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Strategy\QueueState
 *
 * @property int $id_queue_state
 * @property int|null $id_channel
 * @property string|null $queue_state
 * @property int|null $state_priority
 * @property-read \App\Models\Strategy\Channel|null $channel
 * @method static Builder|QueueState newModelQuery()
 * @method static Builder|QueueState newQuery()
 * @method static Builder|QueueState query()
 * @method static Builder|QueueState whereIdChannel( $value )
 * @method static Builder|QueueState whereIdQueueState( $value )
 * @method static Builder|QueueState whereQueueState( $value )
 * @method static Builder|QueueState whereStatePriority( $value )
 * @mixin \Eloquent
 */
class QueueState extends AModel {

    const ST_NEW = 'New';
    const ST_SENT = 'Sent to remote';
    const ST_DELIVERED = 'Delivered';
    const ST_FAILED = 'Failed';


    public $timestamps = false;

    protected $table = 'strategy_dim_queue_state';
    protected $primaryKey = 'id_queue_state';


    public function channel() {
        $keyName = ( new Channel() )->getKeyName();
        return $this->belongsTo( Channel::class, $keyName, $keyName );
    }
}
