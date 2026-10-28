<?php
/**
 * Home page header with quick links. $a: block attributes.
 */

$contact = osteza_anchor( 'contacto' );
?>
<section class="hero dk<?php echo $a['image'] ? ' has-bg' : ''; ?>" aria-labelledby="h1">
<?php if ( $a['image'] ) : ?>
<div class="hero-bg" style="--shade:<?php echo esc_attr( max( 0, min( 100, (int) $a['overlay'] ) ) / 100 ); ?>"><?php echo wp_get_attachment_image( $a['image'], 'full', false, array( 'alt' => '', 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '100vw' ) ); ?></div>
<?php endif; ?>
<div class="hero-glow"></div>
<img class="hero-mark" src="<?php echo osteza_img( 'mark-light.svg' ); ?>" alt="" aria-hidden="true">
<div class="wrap hgrid">
<div class="hcopy">
<?php if ( $a['kicker'] ) : ?><p class="kick"><?php echo esc_html( $a['kicker'] ); ?></p><?php endif; ?>
<h1 id="h1" class="disp"><?php echo osteza_html( $a['title'] ); ?></h1>
<?php if ( $a['lead'] ) : ?><p class="hsub"><?php echo esc_html( $a['lead'] ); ?></p><?php endif; ?>
<div class="hbtns">
<a class="btn btn-p" href="<?php echo $contact; ?>"><?php echo esc_html( $a['button'] ); ?> <?php echo osteza_arrow(); ?></a>
<a class="btn btn-ol" href="<?php echo esc_url( osteza_whatsapp_url() ); ?>" target="_blank" rel="noopener"><?php echo osteza_icon( 'whatsapp', 22 ); ?><?php echo esc_html( $a['button2'] ); ?></a>
</div>
<?php if ( $a['langs'] && count( osteza_languages() ) > 1 ) : ?>
<p class="hlangs"><?php echo osteza_icon( 'chat', 20, '#EBA3AE' ); ?><span><?php echo esc_html( $a['langs'] ); ?></span>
<?php foreach ( osteza_languages() as $l ) : ?><?php if ( $l['current'] ) : ?><strong lang="<?php echo esc_attr( $l['code'] ); ?>"><?php echo esc_html( $l['name'] ); ?></strong><?php else : ?><a href="<?php echo esc_url( $l['url'] ); ?>" lang="<?php echo esc_attr( $l['code'] ); ?>" hreflang="<?php echo esc_attr( $l['code'] ); ?>"><?php echo esc_html( $l['name'] ); ?></a><?php endif; ?><?php endforeach; ?></p>
<?php endif; ?>
</div>
<nav class="qpanel" aria-label="<?php esc_attr_e( 'Accesos rápidos a servicios', 'osteza' ); ?>">
<p class="disp"><?php echo esc_html( $a['quickTitle'] ); ?></p>
<?php foreach ( (array) $a['quick'] as $item ) : ?>
<a class="qlink" href="<?php echo osteza_link( $item['page'] ?? 0 ); ?>"><?php echo esc_html( $item['label'] ?? '' ); ?> <?php echo osteza_arrow(); ?></a>
<?php endforeach; ?>
<?php if ( $a['quickOther'] ) : ?><a class="qlink qlink-x" href="<?php echo $contact; ?>"><?php echo esc_html( $a['quickOther'] ); ?> <?php echo osteza_arrow(); ?></a><?php endif; ?>
</nav>
</div>
<?php if ( $a['facts'] ) : ?>
<div class="wrap"><div class="facts">
<?php foreach ( (array) $a['facts'] as $fact ) : ?>
<div class="fact"><?php echo osteza_icon( isset( osteza_ui_icons()[ $fact['icon'] ?? '' ] ) ? $fact['icon'] : 'house', 28, '#EBA3AE', '1.6' ); ?><span><strong><?php echo esc_html( $fact['title'] ?? '' ); ?></strong><?php echo esc_html( $fact['text'] ?? '' ); ?></span></div>
<?php endforeach; ?>
</div></div>
<?php endif; ?>
</section>
