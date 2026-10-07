<section id="zonas" class="sec" aria-labelledby="h-zone">
<div class="wrap zgrid">
<div class="zstick zcopy rv">
<p class="kick"><?php esc_html_e( 'Zonas', 'osteza' ); ?></p>
<h2 id="h-zone" class="disp h2"><?php esc_html_e( 'Trabajamos en la Costa Blanca.', 'osteza' ); ?></h2>
<p class="lead lead-sm"><?php esc_html_e( 'Si tu localidad no aparece, pregúntanos.', 'osteza' ); ?></p>
<a class="btn btn-o" href="<?php echo osteza_anchor( 'contacto' ); ?>"><?php esc_html_e( 'Consultar disponibilidad en mi zona', 'osteza' ); ?></a>
</div>
<ul class="towns rv">
<?php foreach ( osteza_towns() as $town ) : ?>
<li><a href="#" class="disp"><?php echo esc_html( $town ); ?></a></li>
<?php endforeach; ?>
<li class="townnote"><?php esc_html_e( '[Lista provisional: publicar solo las localidades confirmadas]', 'osteza' ); ?></li>
</ul>
</div>
</section>
