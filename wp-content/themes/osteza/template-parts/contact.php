<?php
/**
 * Contact section with the WhatsApp/Telegram hand-off form.
 *
 * Args: 'kicker', 'title', 'lead', 'service' (option preselected in the form),
 * 'town' (prefills the town field), 'channels' (false hides the grid of contact details).
 */

$kicker   = $args['kicker'] ?? __( 'Contacto', 'osteza' );
$title    = $args['title'] ?? __( 'Cuéntanos qué necesitas.', 'osteza' );
$lead     = $args['lead'] ?? __( 'Rellena el formulario o escríbenos directamente. Te respondemos por el canal que prefieras.', 'osteza' );
$channels = $args['channels'] ?? true;
$selected = $args['service'] ?? '';
$new_tab  = '<span class="sr"> ' . esc_html__( '(abre una pestaña nueva)', 'osteza' ) . '</span>';
$tg_user  = ltrim( osteza_contact( 'telegram' ), '@' );
// Every form uses the same complete, localized catalogue.
$services = array_values( array_unique( wp_list_pluck( osteza_all_services(), 'name' ) ) );
$services[] = __( 'Otro trabajo', 'osteza' );
?>
<section id="contacto" class="sec contact dk" aria-labelledby="h-contact">
<img class="contactbg" src="<?php echo osteza_img( 'mark-light.svg' ); ?>" alt="" aria-hidden="true">
<div class="wrap contactgrid">
<div class="contactcopy rv">
<p class="kick"><?php echo esc_html( $kicker ); ?></p>
<h2 id="h-contact" class="disp"><?php echo esc_html( $title ); ?></h2>
<p class="lead"><?php echo esc_html( $lead ); ?></p>
<?php if ( $channels ) : ?>
<div class="cgrid">
<a class="citem" href="<?php echo esc_attr( osteza_phone_url() ); ?>"><span class="cic"><?php echo osteza_icon( 'phone', 22 ); ?></span><span class="ctx"><span class="clab"><?php esc_html_e( 'Teléfono', 'osteza' ); ?></span> <span class="cval"><?php echo esc_html( osteza_contact( 'phone' ) ); ?></span></span></a>
<a class="citem" href="<?php echo esc_url( osteza_whatsapp_url() ); ?>" target="_blank" rel="noopener"><span class="cic"><?php echo osteza_icon( 'whatsapp', 22 ); ?></span><span class="ctx"><span class="clab">WhatsApp</span> <span class="cval"><?php echo esc_html( osteza_contact( 'whatsapp' ) ); ?><?php echo $new_tab; ?></span></span></a>
<a class="citem" href="<?php echo esc_url( osteza_telegram_url() ); ?>" target="_blank" rel="noopener"><span class="cic"><?php echo osteza_icon( 'telegram', 22 ); ?></span><span class="ctx"><span class="clab">Telegram</span> <span class="cval">@<?php echo esc_html( $tg_user ); ?><?php echo $new_tab; ?></span></span></a>
<a class="citem" href="mailto:<?php echo esc_attr( get_theme_mod( 'osteza_email', '' ) ); ?>"><span class="cic"><?php echo osteza_icon( 'mail', 22 ); ?></span><span class="ctx"><span class="clab"><?php esc_html_e( 'Email', 'osteza' ); ?></span> <span class="cval"><?php echo esc_html( osteza_contact( 'email' ) ); ?></span></span></a>
<div class="citem"><span class="cic"><?php echo osteza_icon( 'pin', 22 ); ?></span><span class="ctx"><span class="clab"><?php esc_html_e( 'Dirección', 'osteza' ); ?></span> <span class="cval"><?php echo esc_html( osteza_contact( 'address' ) ); ?></span></span></div>
<div class="citem"><span class="cic"><?php echo osteza_icon( 'clock', 22 ); ?></span><span class="ctx"><span class="clab"><?php esc_html_e( 'Horario', 'osteza' ); ?></span> <span class="cval"><?php echo esc_html( osteza_contact( 'hours' ) ); ?></span></span></div>
</div>
<?php endif; ?>
<div class="cfollow"><span><?php esc_html_e( 'Síguenos', 'osteza' ); ?></span>
<a class="cpill" href="<?php echo osteza_social_url( 'instagram' ); ?>" target="_blank" rel="noopener"><?php echo osteza_icon( 'instagram', 20 ); ?>Instagram<?php echo $new_tab; ?></a>
<a class="cpill" href="<?php echo osteza_social_url( 'facebook' ); ?>" target="_blank" rel="noopener"><?php echo osteza_icon( 'facebook', 20 ); ?>Facebook<?php echo $new_tab; ?></a>
</div>
<div class="cnote"><?php echo osteza_icon( 'info', 24, '#EBA3AE' ); ?><span><strong><?php esc_html_e( 'Así funciona el formulario.', 'osteza' ); ?></strong> <?php esc_html_e( 'Con WhatsApp abrimos el chat con tu mensaje ya escrito; con Telegram lo copias y lo pegas. Nada se envía sin tu confirmación.', 'osteza' ); ?></span></div>
</div>

<form class="cform rv" method="post" novalidate aria-labelledby="h-form" data-form>
<h3 id="h-form" class="disp"><?php esc_html_e( 'Tu solicitud', 'osteza' ); ?></h3>
<div class="ferr" role="alert" data-form-errors hidden></div>
<div class="formgrid">
<div class="fld"><label for="c-name"><?php esc_html_e( 'Nombre', 'osteza' ); ?></label><input id="c-name" name="name" class="inp" autocomplete="name" aria-describedby="e-name"><span id="e-name" class="err" hidden></span></div>
<div class="fld"><label for="c-phone"><?php esc_html_e( 'Teléfono', 'osteza' ); ?></label><input id="c-phone" name="phone" class="inp" type="tel" inputmode="tel" autocomplete="tel" aria-describedby="e-phone"><span id="e-phone" class="err" hidden></span></div>
<div class="fld"><label for="c-town"><?php esc_html_e( 'Localidad', 'osteza' ); ?></label><input id="c-town" name="town" class="inp" autocomplete="address-level2" aria-describedby="e-town" value="<?php echo esc_attr( $args['town'] ?? '' ); ?>"><span id="e-town" class="err" hidden></span></div>
<div class="fld"><label for="c-service"><?php esc_html_e( 'Servicio', 'osteza' ); ?></label><select id="c-service" name="service" class="inp" aria-describedby="e-service"><option value=""><?php esc_html_e( 'Elige una opción', 'osteza' ); ?></option><?php foreach ( $services as $service ) : ?><option<?php selected( $service, $selected ); ?>><?php echo esc_html( $service ); ?></option><?php endforeach; ?></select><span id="e-service" class="err" hidden></span></div>
</div>
<div class="fld"><label for="c-desc"><?php esc_html_e( 'Mensaje', 'osteza' ); ?></label><textarea id="c-desc" name="desc" class="inp" rows="4" aria-describedby="h-desc e-desc"></textarea><span id="h-desc" class="fhint"><?php esc_html_e( 'Qué hay que hacer, medidas aproximadas y plazo, si lo tienes.', 'osteza' ); ?></span><span id="e-desc" class="err" hidden></span></div>

<noscript><p><a href="<?php echo esc_url( osteza_whatsapp_url() ); ?>">WhatsApp</a> · <a href="<?php echo esc_attr( osteza_phone_url() ); ?>"><?php echo esc_html( osteza_contact( 'phone' ) ); ?></a></p></noscript>
<div class="fbtns" data-state="idle">
<button type="submit" class="btn btn-p" disabled data-handoff><?php echo osteza_icon( 'whatsapp', 22 ); ?><?php esc_html_e( 'Continuar por WhatsApp', 'osteza' ); ?></button>
<button type="button" class="btn btn-o" data-go="tg" disabled data-handoff><?php echo osteza_icon( 'telegram', 22 ); ?><?php esc_html_e( 'Continuar por Telegram', 'osteza' ); ?></button>
</div>

<div class="fdone" role="status" data-state="wa" hidden>
<strong><?php esc_html_e( 'Tu mensaje está preparado', 'osteza' ); ?></strong>
<pre data-msg></pre>
<p><strong><?php esc_html_e( 'No se envía hasta que pulses «Enviar» en WhatsApp.', 'osteza' ); ?></strong> <?php esc_html_e( 'Todavía no hemos recibido nada.', 'osteza' ); ?></p>
<div class="fdbtns"><a class="btn btn-p" href="#" target="_blank" rel="noopener" data-wa-link><?php esc_html_e( 'Abrir WhatsApp', 'osteza' ); ?> <span aria-hidden="true">↗</span><?php echo $new_tab; ?></a><button type="button" class="btn btn-o" data-edit><?php esc_html_e( 'Editar datos', 'osteza' ); ?></button></div>
<?php /* translators: %s: phone number. */ ?>
<p class="fsmall"><?php echo esc_html( sprintf( __( '¿No se abre? Llámanos al %s o usa Telegram.', 'osteza' ), osteza_contact( 'phone' ) ) ); ?></p>
</div>

<div class="fdone" role="status" data-state="tg" hidden>
<strong><?php esc_html_e( 'Copia el mensaje y pégalo en Telegram', 'osteza' ); ?></strong>
<p><?php esc_html_e( 'Telegram no permite enviarnos tus datos automáticamente desde la web.', 'osteza' ); ?></p>
<pre class="fsel" aria-label="<?php esc_attr_e( 'Mensaje para Telegram', 'osteza' ); ?>" data-msg></pre>
<div class="fdbtns"><button type="button" class="btn btn-o" data-copy><?php esc_html_e( 'Copiar mensaje', 'osteza' ); ?></button><a class="btn btn-p" href="<?php echo esc_url( osteza_telegram_url() ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Abrir Telegram', 'osteza' ); ?> <span aria-hidden="true">↗</span><?php echo $new_tab; ?></a></div>
<p class="fcopyfail" data-copy-fail hidden><?php esc_html_e( 'No hemos podido copiarlo automáticamente. Selecciona el texto y cópialo a mano.', 'osteza' ); ?></p>
<button type="button" class="fedit" data-edit><?php esc_html_e( 'Editar datos', 'osteza' ); ?></button>
</div>

<?php get_template_part( 'template-parts/data-notice', null, array( 'chat' => false ) ); ?>
</form>
</div>
</section>
