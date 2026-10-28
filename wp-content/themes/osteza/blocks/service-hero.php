<?php
/**
 * Service page header: breadcrumb with the group, summary card and page index. $a: block attributes.
 */

$contact = osteza_anchor( 'contacto' );
$service = osteza_current_service();
$group   = $service ? osteza_group( $service['group'] ) : null;
$crumbs  = $group && 0 !== strcasecmp( trim( $group['title'] ), trim( get_the_title() ) ) ? array( array( $group['title'], osteza_catalog_url( $group['key'] ) ) ) : array();
?>
<section class="svc-hero" aria-labelledby="h1">
<div class="wrap">
<?php osteza_breadcrumb( $crumbs ); ?>
<div class="shero">
<div class="sherocopy">
<p class="kick"><?php esc_html_e( 'Servicios', 'osteza' ); ?></p>
<h1 id="h1" class="disp"><?php echo osteza_html( $a['title'] ?: get_the_title() ); ?></h1>
<?php if ( $a['lead'] ) : ?><p class="lead"><?php echo esc_html( $a['lead'] ); ?></p><?php endif; ?>
<div class="hbtns">
<a class="btn btn-p" href="<?php echo $contact; ?>"><?php echo esc_html( $a['button'] ); ?> <?php echo osteza_arrow(); ?></a>
<a class="btn btn-o" href="<?php echo esc_url( osteza_whatsapp_url() ); ?>" target="_blank" rel="noopener"><?php echo osteza_icon( 'whatsapp', 22 ); ?><?php echo esc_html( $a['button2'] ); ?></a>
</div>
</div>
<?php if ( $a['summary'] ) : ?>
<aside class="scard dk" aria-label="<?php esc_attr_e( 'Resumen del servicio', 'osteza' ); ?>">
<img class="scardbg" src="<?php echo osteza_img( 'mark-light.svg' ); ?>" alt="" aria-hidden="true">
<p class="disp"><?php echo esc_html( $a['summaryTitle'] ); ?></p>
<dl>
<?php foreach ( (array) $a['summary'] as $row ) : ?>
<div class="srow"><dt><?php echo esc_html( $row['term'] ?? '' ); ?></dt><dd><?php echo esc_html( $row['value'] ?? '' ); ?></dd></div>
<?php endforeach; ?>
</dl>
<?php if ( $a['summaryLink'] ) : ?><a class="lnk" href="<?php echo $contact; ?>"><?php echo esc_html( $a['summaryLink'] ); ?> <?php echo osteza_arrow(); ?></a><?php endif; ?>
</aside>
<?php endif; ?>
</div>
<?php if ( $a['anchors'] ) : ?>
<nav aria-label="<?php esc_attr_e( 'En esta página', 'osteza' ); ?>" class="anch">
<?php foreach ( (array) $a['anchors'] as $anchor ) : ?>
<a href="#<?php echo esc_attr( $anchor['id'] ?? '' ); ?>"><?php echo esc_html( $anchor['label'] ?? '' ); ?></a>
<?php endforeach; ?>
</nav>
<?php endif; ?>
</div>
</section>
