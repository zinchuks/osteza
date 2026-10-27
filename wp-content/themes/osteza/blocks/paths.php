<?php
/**
 * Two entry points (cards). $a: block attributes.
 */

$hid = osteza_uid( 'h-path' );
?>
<section class="sec" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap stack">
<div class="stitle rv"><?php if ( $a['kicker'] ) : ?><p class="kick"><?php echo esc_html( $a['kicker'] ); ?></p><?php endif; ?><h2 id="<?php echo esc_attr( $hid ); ?>" class="disp h2"><?php echo esc_html( $a['title'] ); ?></h2></div>
<div class="paths">
<?php foreach ( (array) $a['cards'] as $card ) : ?>
<?php
$dark = 'light' !== ( $card['style'] ?? 'dark' );
$href = ! empty( $card['page'] ) ? osteza_link( $card['page'] ) : osteza_anchor( 'contacto' );
$tags = osteza_lines( $card['tags'] ?? '' );
?>
<article class="path <?php echo $dark ? 'path-g dk' : 'path-s'; ?> rv">
<?php if ( $dark ) : ?><img class="pathbg" src="<?php echo osteza_img( 'mark-light.svg' ); ?>" alt="" aria-hidden="true"><?php endif; ?>
<?php echo osteza_art( osteza_art_inner( $card['icon'] ?? '' ), 64, $dark ? '#EBA3AE' : '#1D1838' ); ?>
<h3 class="disp"><?php echo esc_html( $card['title'] ?? '' ); ?></h3>
<p><?php echo esc_html( $card['text'] ?? '' ); ?></p>
<?php if ( $tags ) : ?><ul class="ctags"><?php foreach ( $tags as $tag ) : ?><li><?php echo esc_html( $tag ); ?></li><?php endforeach; ?></ul><?php endif; ?>
<?php if ( ! empty( $card['button'] ) ) : ?><a class="btn <?php echo $dark ? 'btn-p' : 'btn-o'; ?>" href="<?php echo $href; ?>"><?php echo esc_html( $card['button'] ); ?> <?php echo osteza_arrow(); ?></a><?php endif; ?>
</article>
<?php endforeach; ?>
</div>
</div>
</section>
