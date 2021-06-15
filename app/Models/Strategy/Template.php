<?php


namespace App\Models\Strategy;


use App\Models\AModel;

/**
 * App\Models\Strategy\Template
 *
 * @property int $id_template
 * @property int $id_channel
 * @property string|null $template_name
 * @property string|null $sms_template
 * @property string|null $ivr_template
 * @property string|null $script_template
 * @property string|null $letter_template
 * @property string|null $email_subj_template
 * @property string|null $email_body_template
 * @property string|null $email_json_params
 * @property string|null $param_json
 * @property string $insert_datetime
 * @property string|null $update_datetime
 * @property int $insert_user_id
 * @property int|null $update_user_id
 * @property string|null $description
 * @property string|null $is_client send to client
 * @property string|null $is_tpc send to third party contact
 * @property string|null $is_inactive send to deactivate contacts
 * @property string|null $is_ptp_sms
 * @property string|null $deleted_at
 * @property int|null $min_phone_piority
 * @property int|null $max_phone_piority
 * @property string $is_active
 * @property string|null $is_pre_tpc_notif flag, that this is notification about disturbing 3rd parties in future. Originated at AsiaKredit
 * @method static \Illuminate\Database\Eloquent\Builder|Template newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Template newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Template query()
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereDeletedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereDescription( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereEmailBodyTemplate( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereEmailJsonParams( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereEmailSubjTemplate( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereIdChannel( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereIdTemplate( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereInsertDatetime( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereInsertUserId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereIsActive( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereIsClient( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereIsInactive( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereIsPreTpcNotif( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereIsPtpSms( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereIsTpc( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereIvrTemplate( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereLetterTemplate( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereMaxPhonePiority( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereMinPhonePiority( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereParamJson( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereScriptTemplate( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereSmsTemplate( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereTemplateName( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereUpdateDatetime( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereUpdateUserId( $value )
 * @mixin \Eloquent
 */
class Template extends AModel {


    public const CREATED_AT = 'insert_datetime';
    public const UPDATED_AT = 'update_datetime';

    protected $table = "strategy_template";
    protected $primaryKey = 'id_template';
}
