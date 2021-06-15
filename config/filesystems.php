<?php
/**
 * IN CASE OF USING FTP
 *
 * 'ftp' => [
 * 'driver'   => 'ftp',
 * 'host'     => 'ftp.example.com',
 * 'username' => 'your-username',
 * 'password' => 'your-password',
 *
 * // Optional FTP Settings...
 * // 'port'     => 21,
 * // 'root'     => '',
 * // 'passive'  => true,
 * // 'ssl'      => true,
 * // 'timeout'  => 30,
 * ],
 */
return [
    'default' => env( 'FILESYSTEM_DRIVER', 'local' ),
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path( 'app' ),
        ],
        'public' => [
            'driver' => 'local',
            'root' => storage_path( 'app/public' ),
            'url' => 'storage',
            'visibility' => 'public',
        ],
        'deal_photo' => [
            'driver' => 'local',
            'root' => storage_path( 'deal/photo' )
        ],
        'deal_attachment' => [
            'driver' => 'local',
            'root' => storage_path( 'deal/attachment' )
        ],
        'gcs' => [
            'driver' => 'gcs',
            'project_id' => env( 'GOOGLE_CLOUD_PROJECT_ID', '' ),
            'key_file' => base_path( env( 'GOOGLE_CLOUD_KEY_FILE', '' ) ),
            'bucket' => env( 'GOOGLE_CLOUD_STORAGE_BUCKET', 'main_bucket' ),
            'path_prefix' => env( 'GOOGLE_CLOUD_STORAGE_PATH_PREFIX', null ),
            'temp_dir' => storage_path( env( 'GCS_TEMP_DIR', 'gcm_temp' ) ),
        ],
    ],
];

