<?php
/**
 * About page header with photo. $a: block attributes.
 */
?>
<section class="phead" aria-labelledby="h1">
<div class="wrap">
<?php osteza_breadcrumb(); ?>
<div class="ahero">
<div class="aherocopy">
<?php if ( $a['kicker'] ) : ?><p class="kick"><?php echo esc_html( $a['kicker'] ); ?></p><?php endif; ?>
<h1 id="h1" class="disp"><?php echo osteza_html( $a['title'] ); ?></h1>
<?php if ( $a['lead'] ) : ?><p class="lead"><?php echo esc_html( $a['lead'] ); ?></p><?php endif; ?>
<div class="hbtns"><a class="btn btn-p" href="<?php echo osteza_anchor( 'contacto' ); ?>"><?php echo esc_html( $a['button'] ); ?> <?php echo osteza_arrow(); ?></a><?php if ( $a['button2'] ) : ?><a class="btn btn-o" href="#equipo-l"><?php echo esc_html( $a['button2'] ); ?></a><?php endif; ?></div>
</div>
<figure class="tphoto dk<?php echo $a['image'] ? ' has-photo' : ''; ?>">
<?php if ( $a['image'] ) : ?>
<?php echo wp_get_attachment_image( $a['image'], 'large', false, array( 'class' => 'photo' ) ); ?>
<?php else : ?>
<img src="<?php echo osteza_img( 'mark-light.svg' ); ?>" alt="" aria-hidden="true">
<figcaption><?php echo esc_html( $a['caption'] ); ?></figcaption>
<?php endif; ?>
</figure>
</div>
</div>
</section>
