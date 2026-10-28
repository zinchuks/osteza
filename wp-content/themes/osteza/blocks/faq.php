<?php
/**
 * Questions and answers, with optional links under the title. $a: block attributes.
 */

$hid   = osteza_uid( 'h-faq' );
$id    = $a['anchor'] ? ' id="' . esc_attr( $a['anchor'] ) . '"' : '';
$links = array_filter(
	(array) $a['links'],
	function ( $link ) {
		return ! empty( $link['page'] ) && '#' !== osteza_link( $link['page'] );
	}
);
?>
<section<?php echo $id; ?> class="sec <?php echo esc_attr( $a['variant'] ); ?>" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap faqgrid">
<div class="faqcopy rv">
<?php if ( $a['kicker'] ) : ?><p class="kick"><?php echo esc_html( $a['kicker'] ); ?></p><?php endif; ?>
<h2 id="<?php echo esc_attr( $hid ); ?>" class="disp h2"><?php echo esc_html( $a['title'] ); ?></h2>
<?php if ( $a['lead'] ) : ?><p class="lead lead-sm"><?php echo osteza_html( $a['lead'] ); ?></p><?php endif; ?>
<?php if ( $links ) : ?>
<div class="znear"><?php if ( $a['linksLabel'] ) : ?><span><?php echo esc_html( $a['linksLabel'] ); ?></span><?php endif; ?><?php foreach ( $links as $link ) : ?><a class="tagp" href="<?php echo osteza_link( $link['page'] ); ?>"><?php echo esc_html( ! empty( $link['label'] ) ? $link['label'] : get_the_title( $link['page'] ) ); ?> →</a><?php endforeach; ?></div>
<?php endif; ?>
</div>
<div class="faq">
<?php foreach ( array_values( (array) $a['items'] ) as $i => $item ) : ?>
<details<?php echo 0 === $i ? ' open' : ''; ?>><summary><?php echo esc_html( $item['q'] ?? '' ); ?><span class="pm" aria-hidden="true"><span></span><span></span></span></summary><p><?php echo osteza_html( $item['a'] ?? '' ); ?></p></details>
<?php endforeach; ?>
</div>
</div>
</section>
