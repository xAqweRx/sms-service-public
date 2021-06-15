<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 10/25/2018
 * Time: 1:18:29 PM
 */

namespace App\Constants;


class ErrorCodes {
    const LOGIN_ABSENT = "E00001";
    const LOGIN_NOT_EXIST = "E00002";
    const LOGIN_AND_PASSWORD_NOT_CORRECT = "E00003";
    const PASSWORD_ABSENT = "E00004";

    const ACCESS_TOKEN_EXPIRED = "E00006";
    const NO_COMPANY_HASH_PROVIDED = "E00007";
    const BAD_COMPANY_HASH_PROVIDED = "E00008";
    const NO_READ_PERMISSION = "E00009";
    const NO_WRITE_PERMISSION = "E00010";
    const NO_DELETE_PERMISSION = "E00011";
    const USER_NOT_AUTHORIZED = "E00012";

    const LOGIN_NOT_UNIQUE = "E00013";
    const FULL_NAME_ABSENT = "E00014";
    const EMAIL_ABSENT = "E00015";
    const COMPANY_NAME_ABSENT = "E00016";
    const COMPANY_NAME_NOT_UNIQUE = "E00017";
    const SUBSCRIPTION_ABSENT = "E00018";
    const SUBSCRIPTION_DOESNT_EXIST = "E00019";

    const FAILED_TO_CREATE_COMPANY = "E00020";
    const EMAIL_NOT_VALID = "E00021";
    const ACCESS_TOKEN_ERROR = "E00022";
    const INCORRECT_PARAMETERS = "E00023";


    // strategy validator
    const STRATEGY_NAME_REQUIRED = "E00024";
    const STRATEGY_GROUP_REQUIRED = "E00025";
    const FORMAT_INVALID = "E00026";
    const UNAUTHORIZED_EXCEPTION = "E00027";
    const INTERNAL_EXCEPTION = "E00028";

    // ACTION TYPE validator
    const ACTION_TYPE_DO_NOT_EXISTS = "E00029";

    //TEMPLATE validator
    const CHANNEL_WAS_NOT_SET = "E00030";
    const CHANNEL_NOT_EXISTS = "E00031";
    const TEMPLATE_NAME_WAS_NOT_SET = "E00032";
    const SMS_TEMPLATE_WAS_NOT_SET = "E00033";
    const IVR_TEMPLATE_WAS_NOT_SET = "E00034";
    const LETTER_TEMPLATE_WAS_NOT_SET = "E00035";
    const SCRIPT_TEMPLATE_WAS_NOT_SET = "E00036";


    //OTP
    const PHONE_ABSENT = "E00037";
    const PHONE_NOT_EXIST = "E00038";
    const PHONE_NOT_VALID = "E00039";

    const OTP_ABSENT = "E00040";
    const OTP_NOT_EXIST = "E00041";
    const OTP_EXPIRED = "E00042";
    const OTP_TYPE_INCORRECT = "E00043";
    const OTP_TYPE_ABSENT = "E00044";

    const SIGN_IN_FAILED = "E00045";

    const INCORRECT_TYPE = "E00046";
    const FIELD_DOESNT_EXIST = "E00047";
    const INCORRECT_SORT_ORDER_VALUE = "E00048";
    const MAX_VALUE_EXCEEDED = "E00049";
    const PARAMETER_ABSENT = "E00050";
    const PARAMETER_EMPTY = "E00051";
    const USER_NOT_EXIST = "E00052";
    const CASE_NOT_EXIST = "E00053";
    const ENTITY_NOT_FOUND = "E00054";
    const CANNOT_REQUEST_NEW_CASE = "E00271";

    // deals to strategy error
    const NO_DEALS_PROVIDED = "E00055";
    const NOT_ARRAY_PROVIDED = "E00056";
    const NO_STRATEGY_ID_PROVIDED = "E00057";
    const STRATEGY_NOT_EXISTS = "E00058";
    const OTP_STILL_VALID = "E00059";
    const COMPILATION_DATA_EMPTY = "E00060";
    const ITEM_NOT_EXIST = "E00061";

    const NO_REMOTE_API_KEY_PROVIDED = "E00062";
    const BAD_PARTNER_API_KEY_PROVIDED = "E00063";


    const EMAIL_TEMPLATE_PARAMS_WAS_NOT_SET = "E00064";


    //VTT dictionary errors
    const WORD_NAME_WAS_NOT_SET = "E00090";
    const WORD_DESCRIPTION_WAS_NOT_SET = "E00091";
    const ID_DICTIONARY_WAS_NOT_SET = "E00092";
    const DICTIONARY_ID_MUST_BE_INT = "E00093";
    const WORD_NAME_NOT_UNIQUE = "E00094";

    const REQUIRED_FIELD = "E00100";
    const MUST_BE_FILLED = "E00101";


    const OBJECT_BY_ID_NOT_EXISTS = "E00150";
    const OBJECT_EXISTS = "E00151";

    const CANT_HAVE_MORE_THAN_ONE_ASSIGNED = "E00160";

    const DEAL_ID_NOT_PROVIDED = "E00170";
    const DEAL_HISTORY_TYPE_NOT_AVAILABLE = "E00171";
    const DEAL_HISTORY_TYPE_NOT_PROVIDED = "E00172";
    const DEAL_HISTORY_TYPE_FILTER_NOT_PROVIDED = "E00173";

    const VICIDIAL_OPERATION_FAILED = "E00180";
    const OPERATOR_NOT_IN_CALL = "E00181";

    const PAGINATION_PAGE_FIELD_NOT_INTEGER = "E00190";

    const ID_NOT_PROVIDED = "E00200";
    const HIGHER_THEN_MAX_ITEMS = "E00201";
    const LESS_THEN_MIN_ITEMS = "E00202";
    const DATE_LESS_THEN_POSSIBLE = "E00203";

    const PROCEDURE_FAILED = "E00210";
    const ALREADY_LOGGED_IN = "E00220";

    const OTP_TIMEOUT = "E00230";
    const OTP_INCORRECT = "E00231";

    const DYNAMIC_SCRIPT_IS_FORBIDDEN = "E00240";

    const INCORRECT_MIME_TYPE = "E00250";
    const MAX_SIZE_EXCEEDED = "E00251";

    const VALUE_NOT_UNIQUE = "E00261";

    /* QC  */
    const QC_REDIS_CURRENT_DATA_NOT_FOUND = "E00262";
    const QC_MYSQL_CURRENT_DATA_NOT_FOUND = "E00263";

    const NO_CASE_ASSIGNED = "E00270";

    const INCORRECT_VALUE = "E00272";

    const PASSWORD_ALREADY_EXISTS = "E00273";
    const PASSWORD_NOT_SECURE = "E00274";
    const VICI_CREDENTIALS_NOT_FOUND = "E00275";

    /*  DB procedure errors   */
    const MANDATORY_FIELD_MISSING = "10001";
    const FIELD_BANK_INCORRECT_VALUE = "10002";
    const FIELD_PROCESSING_INCORRECT_VALUE = "10003";
}

