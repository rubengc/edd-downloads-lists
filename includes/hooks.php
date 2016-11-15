<?php
/**
 * Hooks
 *
 * @package     EDD\Downloads_Lists\Hooks
 * @since       1.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Prevents EDD Downloads Lists count metas be overwritten on EDD FES Draft download approbation process
 *
 * @since   1.0
 * @param   $excluded_metas array   Array with metas to exclude from EDD FES Draft
 * @return  array                   Metas to exclude on draft download approbation
 */
function edd_downloads_lists_approve_download_excluded_metas( $excluded_metas ) {
    foreach( edd_downloads_lists()->get_lists() as $list => $list_args ) {
        $excluded_metas[] = sprintf( 'edd_downloads_lists_%s_count', $list );
    }

    return $excluded_metas;
}
add_filter( 'edd_fes_draft_approve_download_excluded_metas', 'edd_downloads_lists_approve_download_excluded_metas' );