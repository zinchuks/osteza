<?php
/**
 * Latest articles: the featured one first. $a: block attributes.
 */

$count    = max( 1, (int) $a['count'] );
$featured = osteza_featured_post();
$posts    = get_posts(
	array(
		'posts_per_page'      => $featured ? $count - 1 : $count,
		'ignore_sticky_posts' => true,
		'post__not_in'        => $featured ? array( $featured->ID ) : array(),
	)
);
if ( $featured ) {
	array_unshift( $posts, $featured );
}
if ( ! $posts ) {
	return;
}
$hid = osteza_uid( 'h-blog' );
?>
<section id="blog" class="sec blog" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
<div class="wrap stack">
<div class="shead rv">
<div class="stitle"><?php if ( $a['kicker'] ) : ?><p class="kick"><?php echo esc_html( $a['kicker'] ); ?></p><?php endif; ?><h2 id="<?php echo esc_attr( $hid ); ?>" class="disp h2"><?php echo esc_html( $a['title'] ); ?></h2></div>
<?php if ( $a['linkLabel'] ) : ?><a class="lnk" href="<?php echo osteza_blog_url(); ?>"><?php echo esc_html( $a['linkLabel'] ); ?> <?php echo osteza_arrow(); ?></a><?php endif; ?>
</div>
<div class="bgrid">
<?php
foreach ( $posts as $p ) {
	get_template_part( 'template-parts/blog/card', null, array( 'post' => $p, 'tag' => 'article', 'class' => 'bcard rv', 'icon' => 48 ) );
}
?>
</div>
</div>
</section>
