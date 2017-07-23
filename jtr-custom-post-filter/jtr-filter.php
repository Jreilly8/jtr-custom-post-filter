<?php
/*
PLUGIN NAME: JTR Custom Post Filter

DESCRIPTION: Filters the custom post permalink for better SEO
AUTHOR: Joseph Reilly
AUTHOR URI: http://jupiterhost.com/
VERSION: 0.1 &beta;
*/

/*
RPM National Custom Field Widget
by Joseph Reilly

JTR Custom Post Filter is free software: you can redistribute it and/or
modify it under the terms of the GNU General Public License as
published by the Free Software Foundation, either version 3 of
the License, or (at your option) any later version.

JTR Custom Post Filter  is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty
of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
See the GNU General Public License for details.

You should have received a copy of the GNU General Public License
along with RPM National Custom Field Widget. If not, see www.gnu.org/licenses/.
*/
/**
 * Remove the slug from published post permalinks. Only affect our custom post type, though.
 */
function jtr_remove_cpt_slug( $post_link, $post, $leavename ) {
 
    if ( 'practice' != $post->post_type || 'publish' != $post->post_status ) {
        return $post_link;
    }
 
    $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );
 
    return $post_link;
}
add_filter( 'post_type_link', 'jtr_remove_cpt_slug', 10, 3 );


/**
 * Have WordPress match postname to any of our public post types (post, page, race)
 * All of our public post types can have /post-name/ as the slug, so they better be unique across all posts
 * By default, core only accounts for posts and pages where the slug is /post-name/
 */
function jtr_parse_request_trick( $query ) {
 
    // Only noop the main query
    if ( ! $query->is_main_query() )
        return;
 
    // Only noop our very specific rewrite rule match
    if ( 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
        return;
    }
 
    // 'name' will be set if post permalinks are just post_name, otherwise the page rule will match
    if ( ! empty( $query->query['name'] ) ) {
        $query->set( 'post_type', array( 'post', 'page', 'practice' ) );
    }
}
add_action( 'pre_get_posts', 'jtr_parse_request_trick' );

?>