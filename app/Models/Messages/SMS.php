<?php


namespace App\Models\Messages;


use App\Models\AModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Messages\SMS
 *
 * @property int|null $id_queue
 * @property int $id_sms
 * @property int $id_template
 * @property int $id_deal
 * @property int|null $id_target
 * @property string|null $arc_date
 * @property string $phone_no
 * @property string|null $sms_text
 * @property int $id_state strategy_dim_queue_state id_channel=1
 * @property int|null $id_gateway
 * @property int|null $priority
 * @property string $insert_datetime
 * @property string|null $update_datetime
 * @property string|null $send_datetime
 * @method static Builder|SMS newModelQuery()
 * @method static Builder|SMS newQuery()
 * @method static Builder|SMS query()
 * @method static Builder|SMS whereArcDate( $value )
 * @method static Builder|SMS whereIdDeal( $value )
 * @method static Builder|SMS whereIdGateway( $value )
 * @method static Builder|SMS whereIdQueue( $value )
 * @method static Builder|SMS whereIdSms( $value )
 * @method static Builder|SMS whereIdState( $value )
 * @method static Builder|SMS whereIdTarget( $value )
 * @method static Builder|SMS whereIdTemplate( $value )
 * @method static Builder|SMS whereInsertDatetime( $value )
 * @method static Builder|SMS wherePhoneNo( $value )
 * @method static Builder|SMS wherePriority( $value )
 * @method static Builder|SMS whereSendDatetime( $value )
 * @method static Builder|SMS whereSmsText( $value )
 * @method static Builder|SMS whereUpdateDatetime( $value )
 * @mixin \Eloquent
 */
class SMS extends AModel {

    const TABLE_NAME = 'strategy_sms_list';
    const PRIMARY_KEY = 'id_sms';

    protected $table = self::TABLE_NAME;
    protected $primaryKey = self::PRIMARY_KEY;


}


