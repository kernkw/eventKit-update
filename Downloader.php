<?php

unlink( dirname(__FILE__).DIRECTORY_SEPARATOR."index.php" );

$manifest_location = "https://raw.githubusercontent.com/sendgrid/eventkit/master/manifest.js";
$manifest_json = get_data( $manifest_location );
$manifest = json_decode( $manifest_json, true );
$current_version = $manifest['current_version'];
$version = $manifest['versions'][$current_version];
$url_prefix = $version['url'];
$files = $version['files'];
$local = explode( DIRECTORY_SEPARATOR, dirname( __FILE__ ) );

processManifest( $files, $local, explode( "/", $url_prefix ) );

function processManifest( $manifest, $local_path, $remote_path ) {
    if ( is_array( $manifest ) ) {
        foreach ( $manifest as $key => $value ) {
            if ( is_array( $value ) ) {
                $new_local_path = array_merge( $local_path, array( $key ) );
                $new_remote_path = array_merge( $remote_path, array( $key ) );
                createFolder( join( DIRECTORY_SEPARATOR, $new_local_path ) );
                processManifest( $value, $new_local_path, $new_remote_path );
            } else {
                $full_local_path = array_merge( $local_path, array( $key ) );
                $full_remote_path = array_merge( $remote_path, array( $key ) );
                $file_contents = get_data( join( "/", $full_remote_path ) );
                file_put_contents( join( DIRECTORY_SEPARATOR, $full_local_path ), $file_contents );
            }
        }
    }
}

function createFolder( $folder ) {
    if ( !file_exists( $folder ) and !is_dir( $folder ) ) {
        $oldumask = umask( 0 );
        mkdir( $folder, 0777 );
        umask( $oldumask );
    }
}

function get_data($url) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

?>

<html>
<head>

    <script>
        function init() {
            window.location = "Installer.php";
        }
    </script>
</head>
<body onload="init();">

</body>
</html>