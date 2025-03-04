<?php
if ( 'originals' === $kind ) {
	$gp_title = sprintf(
		/* translators: %s: Project name. */
		__( 'Import Originals &lt; %s &lt; GlotPress', 'glotpress' ),
		esc_html( $project->name )
	);
	$return_link = gp_url_project( $project );
	gp_breadcrumb_project( $project );
} else {
	$gp_title = sprintf(
		/* translators: %s: Project name. */
		__( 'Import Translations &lt; %s &lt; GlotPress', 'glotpress' ),
		esc_html( $project->name )
	);
	$return_link = gp_url_project_locale( $project, $locale->slug, $translation_set->slug );
	gp_breadcrumb(
		array(
			gp_project_links_from_root( $project ),
			gp_link_get( $return_link, $translation_set->name ),
		)
	);
}

gp_title( $gp_title );
gp_tmpl_header();

?>
<h2><?php echo 'originals' == $kind ? __( 'Import Originals', 'glotpress' ) : __( 'Import Translations', 'glotpress' ); ?></h2>
<form action="" method="post" enctype="multipart/form-data">
	<dl>
	<dt><label for="import-file"><?php _e( 'Import File:', 'glotpress' ); ?></label></dt>
	<dd><input type="file" name="import-file" id="import-file" accept="<?php echo esc_attr( implode( ',', gp_get_format_extensions() ) ); ?>" /></dd>
<?php

	$format_options         = array();
	$format_options['auto'] = __( 'Auto Detect', 'glotpress' );
	foreach ( GP::$formats as $slug => $format ) {
		$format_options[ $slug ] = $format->name;
	}

	$status_options = array();
	if ( isset( $can_import_current ) && $can_import_current ) {
		$status_options['current'] = __( 'Current', 'glotpress' );
	}
	if ( isset( $can_import_waiting ) && $can_import_waiting ) {
		$status_options['waiting'] = __( 'Waiting', 'glotpress' );
	}
?>
	<dt><label for="format"><?php _e( 'Format:', 'glotpress' ); ?></label></dt>
	<dd>
		<?php echo gp_select( 'format', $format_options, 'auto' ); ?>
	</dd>
<?php if ( ! empty( $status_options ) ) : ?>
	<dt><label for="status"><?php _e( 'Status:', 'glotpress' ); ?></label></dt>
	<dd>
		<?php if ( count( $status_options ) === 1 ) : ?>
			<input type="hidden" name="status" value="<?php echo esc_attr( key( $status_options ) ); ?>" />
			<?php echo esc_html( reset( $status_options ) ); ?>
		<?php elseif ( count( $status_options ) > 1 ) : ?>
			<?php echo gp_select( 'status', $status_options, 'current' ); ?>
		<?php endif; ?>
	</dd>
<?php endif; ?>
	<dt>
		<div class="button-group">
			<input class="button is-primary" type="submit" name="submit" value="<?php esc_attr_e( 'Import', 'glotpress' ); ?>" id="submit" />
			<a class="button is-link" href="<?php echo esc_url( $return_link ); ?>"><?php _e( 'Cancel', 'glotpress' ); ?></a>
		</div>
	</dt>
	</dl>
	<?php gp_route_nonce_field( ( 'originals' === $kind ? 'import-originals_' : 'import-translations_' ) . $project->id ); ?>
</form>

<?php
gp_tmpl_footer();
