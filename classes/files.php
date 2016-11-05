<?php

/**
 * Handles file uploading from file fields
 *
 * @package Caldera_Forms
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 CalderaWP LLC
 */
class Caldera_Forms_Files{

    const CRON_ACTION = 'caldera_forms_delete_files';

    /**
     * Holds upload dir path for non-media library uploads
     *
     * @since 1.4.4
     *
     * @var string
     */
    protected static $dir;

    /**
     * Upload a file to WordPress
     *
     * @since 1.4.4
     *
     * @param array $file File
     * @param array $args Optional. Used to place in private dir
     *
     * @return array
     */
    public static function upload( $file, array  $args = array() ){
        $args = wp_parse_args($args, array(
            'private' => false,
            'field_id' => null,
            'form_id' => null,
        ));
        if( true == $args[ 'private' ] && ! empty( $args[ 'field_id' ] ) && ! empty( $args[ 'form_id' ] )){
            $private = true;
        }else{
            $private = false;
        }

        if( $private ){
            wp_schedule_single_event( time() + HOUR_IN_SECONDS, self::CRON_ACTION, array(
                $args[ 'field_id' ],
                $args[ 'form_id' ]
            ) );
            self::add_upload_filter( $args[ 'field_id' ],  $args[ 'form_id' ] );
        }

        $upload = wp_handle_upload($file, array( 'test_form' => false ), date('Y/m') );

        if( $private ){
            self::remove_upload_filter();
        }

        return $upload;

    }

    /**
     * Add uploaded file to media library
     *
     * @since 1.4.4
     *
     * @param array $upload Uploaded file data
     */
    public static function add_to_media_library( $upload ){
        require_once( ABSPATH . 'wp-admin/includes/media.php' );
        require_once( ABSPATH . 'wp-admin/includes/image.php' );

        $media_item = array(
            'guid'           => $upload['file'],
            'post_mime_type' => $upload['type'],
            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $upload['file'] ) ),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        $media_id = wp_insert_attachment( $media_item, $upload['file'] );

        $media_data = wp_generate_attachment_metadata( $media_id, $upload['file'] );
        wp_update_attachment_metadata( $media_id, $media_data );

    }

    /**
     * Setup upload directory filter
     *
     * @since 1.4.4
     *
     * @param string $field_id The field ID for file field
     * @param string $form_id The form ID
     */
    public static function add_upload_filter( $field_id , $form_id ){
        self::$dir = self::secret_dir( $field_id, $form_id );
        add_filter( 'upload_dir', array( __CLASS__, 'uploads_filter' ) );
    }

    /**
     * Remove the filter for upload directory path
     *
     * @since 1.4.4
     */
    public static function remove_upload_filter(){
        remove_filter( 'upload_dir', array( __CLASS__, 'uploads_filter' ) );
    }

    /**
     * Filter upload directory
     *
     * @uses "upload_dir" filter
     *
     * @since 1.4.4
     *
     * @param array $args
     * @return array
     */
    public static function uploads_filter( $args ){

        $newdir = self::$dir;

        $args['path']    = str_replace( $args['subdir'], '', $args['path'] );
        $args['url']     = str_replace( $args['subdir'], '', $args['url'] );
        $args['subdir']  = $newdir;
        $args['path']   .= $newdir;
        $args['url']    .= $newdir;

        return $args;
    }

    /**
     * Get a secret file fir by field ID and form ID
     *
     * @since 1.4.4
     *
     * @param string $field_id The field ID for file field
     * @param string $form_id The form ID
     *
     * @return string
     */
    protected static function secret_dir( $field_id, $form_id ){
        return md5( $field_id . $form_id . NONCE_SALT );

    }

    /**
     * Delete all files from the secret dir for a field
     *
     * @since 1.4.4
     *
     * @param string $field_id The field ID for file field
     * @param string $form_id The form ID
     */
    protected  function delete_uploaded_files( $field_id, $form_id ){

        $dir = self::secret_dir($field_id, $form_id);
        if (is_dir($dir)) {
            array_map('unlink', glob($dir . '/*'));
            rmdir($dir);
        }

    }

    /**
     * After form submit, clear out files from secret dirs
     *
     * @since 1.4.4
     *
     * @param array $form Form config
     * @param bool $second_run Optional. If using at mail hooks, set true to prevent recurrsion
     */
    public function cleanup( $form, $second_run = false ){
        if( false === $second_run && Caldera_Forms::should_send_mail( $form ) ) {
            add_action( 'caldera_forms_mailer_complete', array( __CLASS__, 'delete_after_mail' ), 10, 3 );
            add_action( 'caldera_forms_mailer_failed', array( __CLASS__, 'delete_after_mail' ), 10, 3 );
            return;
        }

        $form_id = $form[ 'ID' ];
        $fields = Caldera_Forms_Forms::get_fields( $form, false );
        foreach( $fields as $id => $field ){
            if( 'advanced_file' == $field[ 'type' ] ){
                self::delete_uploaded_files( $field[ 'ID' ], $form_id );
            }

        }

    }

    /**
     * Do cleanup after sending email
     *
     * We use "caldera_forms_submit_complete" to start the clean up, but that is too soon, if using mailer.
     *
     * @since 1.4.4
     *
     * @param $mail
     * @param $data
     * @param $form
     */
    public static function delete_after_mail( $mail, $data, $form ){
        self::cleanup( $form, true );
    }

    /**
     * Trigger file delete via CRON
     *
     * This is needed because if a form never completed submission, files are not deleted at caldera_forms_submit_complete
     *
     * @since 1.4.4
     *
     * @param array $args
     */
    public static function cleanup_via_cron( $args ){
        if( isset( $args[0], $args[1] ) ){
            self::delete_uploaded_files( $args[0], $args[1] );
        }

    }

}