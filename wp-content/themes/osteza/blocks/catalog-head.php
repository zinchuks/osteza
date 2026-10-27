<?php
/**
 * Services catalogue header: search box, finder and group anchors. $a: block attributes.
 */

$contact = osteza_anchor( 'contacto' );
$catalog = osteza_catalog();
?>
<section class="phead" aria-labelledby="h1">
<div class="wrap">
<?php osteza_breadcrumb(); ?>
<div class="chero">
<div class="cherocopy">
<?php if ( $a['kicker'] ) : ?><p class="kick"><?php echo esc_html( $a['kicker'] ); ?></p><?php endif; ?>
<h1 id="h1" class="disp"><?php echo osteza_html( $a['title'] ); ?></h1>
<?php if ( $a['lead'] ) : ?><p class="lead"><?php echo esc_html( $a['lead'] ); ?></p><?php endif; ?>
<form role="search" class="csearch" data-catalog-search>
<label for="sq" class="sr"><?php esc_html_e( 'Buscar un servicio', 'osteza' ); ?></label>
<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
<input id="sq" type="search" placeholder="<?php echo esc_attr( $a['placeholder'] ); ?>">
</form>
</div>
<?php if ( $a['finder'] ) : ?>
<nav class="finder dk" aria-labelledby="fd-t">
<img class="finderbg" src="<?php echo osteza_img( 'mark-light.svg' ); ?>" alt="" aria-hidden="true">
<p id="fd-t" class="disp"><?php echo esc_html( $a['finderTitle'] ); ?></p>
<?php foreach ( (array) $a['finder'] as $option ) : ?>
<?php $group = osteza_group( $option['group'] ?? 0 ); ?>
<a class="fopt" href="#g-<?php echo esc_attr( $group ? $group['key'] : '' ); ?>"><span class="fic"><?php echo osteza_art_icon( $option['icon'] ?? '', 24, 'currentColor', '2.4' ); ?></span><span><strong><?php echo esc_html( $option['label'] ?? '' ); ?></strong><span><?php echo esc_html( $option['sub'] ?? '' ); ?></span></span><?php echo osteza_arrow(); ?></a>
<?php endforeach; ?>
</nav>
<?php endif; ?>
</div>
<nav aria-label="<?php esc_attr_e( 'Grupos de servicios', 'osteza' ); ?>" class="anch">
<?php foreach ( $catalog as $key => $group ) : ?>
<a href="#g-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $group['title'] ); ?> <span class="anum" data-count="g-<?php echo esc_attr( $key ); ?>"><?php echo count( $group['items'] ); ?></span></a>
<?php endforeach; ?>
</nav>
<?php /* translators: %s: what the visitor searched for. */ ?>
<div class="cnone" role="status" data-catalog-none hidden><div><strong><?php echo wp_kses( sprintf( __( 'No encontramos «%s».', 'osteza' ), '<span data-query></span>' ), array( 'span' => array( 'data-query' => true ) ) ); ?></strong><span><?php echo esc_html( $a['noneText'] ); ?></span></div><a class="btn btn-p" href="<?php echo $contact; ?>"><?php echo esc_html( $a['noneButton'] ); ?></a></div>
</div>
</section>
