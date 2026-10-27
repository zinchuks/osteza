<?php
/**
 * Live chat window and its request form (inc/chat.php), driven by main.js.
 */
?>
<div class="chat" data-chat>
<div class="chat-tease" data-chat-tease hidden>
<button class="chat-tease-b" type="button" data-chat-tease-open><strong><?php esc_html_e( '👋 ¿Tienes alguna pregunta?', 'osteza' ); ?></strong><span><?php esc_html_e( 'Escríbenos en el chat: respondemos al momento.', 'osteza' ); ?></span></button>
<button class="chat-tease-x" type="button" data-chat-tease-close aria-label="<?php esc_attr_e( 'Cerrar el aviso', 'osteza' ); ?>"><?php echo osteza_icon( 'close', 14 ); ?></button>
</div>
<button class="chat-fab" type="button" data-chat-open aria-expanded="false" aria-controls="chatbox" aria-label="<?php esc_attr_e( 'Abrir el chat', 'osteza' ); ?>"><?php echo osteza_icon( 'chat', 24 ); ?><span class="chat-fab-t"><?php esc_html_e( 'Chat en línea', 'osteza' ); ?></span><i class="chat-on" aria-hidden="true"></i></button>
<section class="chat-box" id="chatbox" role="dialog" aria-labelledby="chat-title" hidden>
<header class="chat-hd">
<span class="chat-av"><img src="<?php echo osteza_img( 'mark-light.svg' ); ?>" alt="" width="22" height="22"></span>
<div class="chat-who"><strong id="chat-title">OSTEZA</strong><span><i class="chat-on" aria-hidden="true"></i><?php esc_html_e( 'En línea · respondemos al momento', 'osteza' ); ?></span></div>
<button class="chat-x" type="button" data-chat-close aria-label="<?php esc_attr_e( 'Cerrar el chat', 'osteza' ); ?>"><?php echo osteza_icon( 'close', 20 ); ?></button>
</header>
<div class="chat-log" data-chat-log aria-live="polite">
<form class="chat-lead" data-chat-lead hidden novalidate>
<p class="chat-lead-t"><?php esc_html_e( 'Déjanos tus datos y te contactamos', 'osteza' ); ?></p>
<label><span><?php esc_html_e( 'Nombre', 'osteza' ); ?></span><input name="name" autocomplete="name" maxlength="80" required></label>
<label><span><?php esc_html_e( 'Teléfono', 'osteza' ); ?></span><input name="phone" type="tel" autocomplete="tel" inputmode="tel" placeholder="+34 600 000 000" required></label>
<label><span><?php esc_html_e( 'Localidad o dirección', 'osteza' ); ?></span><input name="town" autocomplete="address-level2" list="chat-towns" maxlength="160" required></label>
<datalist id="chat-towns"><?php foreach ( osteza_towns() as $town ) : ?><option value="<?php echo esc_attr( html_entity_decode( $town, ENT_QUOTES, 'UTF-8' ) ); ?>"><?php endforeach; ?></datalist>
<div class="chat-geo"><button type="button" data-chat-geo><?php echo osteza_icon( 'marker', 16 ); ?><span><?php esc_html_e( 'Compartir mi ubicación', 'osteza' ); ?></span></button><span data-chat-geo-state></span><input type="hidden" name="geo"></div>
<fieldset><legend><?php esc_html_e( '¿Dónde te respondemos?', 'osteza' ); ?></legend>
<div class="chat-ch">
<?php foreach ( osteza_chat_channels() as $key => $label ) : ?>
<label><input type="radio" name="channel" value="<?php echo esc_attr( $key ); ?>"<?php checked( 'whatsapp', $key ); ?>><span><?php echo esc_html( $label ); ?></span></label>
<?php endforeach; ?>
</div>
</fieldset>
<label data-chat-tg hidden><span><?php esc_html_e( 'Usuario de Telegram (opcional)', 'osteza' ); ?></span><input name="telegram" autocomplete="off" placeholder="@usuario" maxlength="40"></label>
<label><span><?php esc_html_e( '¿Qué necesitas?', 'osteza' ); ?></span><textarea name="request" rows="3" maxlength="2000" required></textarea></label>
<div class="chat-ph">
<label class="chat-ph-add"><input type="file" name="photos" accept="image/*" multiple data-chat-photos><?php echo osteza_icon( 'doc', 18 ); ?><span><?php esc_html_e( 'Añadir fotos (hasta 5)', 'osteza' ); ?></span></label>
<div class="chat-ph-list" data-chat-thumbs></div>
</div>
<p class="chat-lead-err" data-chat-err hidden></p>
<div class="chat-lead-btns">
<button class="btn btn-p" type="submit" data-chat-submit><?php esc_html_e( 'Enviar solicitud', 'osteza' ); ?></button>
<button class="chat-lead-back" type="button" data-chat-back><?php esc_html_e( 'Volver al chat', 'osteza' ); ?></button>
</div>
</form>
</div>
<div class="chat-tools"><button class="chat-human" type="button" data-chat-human><?php echo osteza_icon( 'phone', 16 ); ?><?php esc_html_e( 'Hablar con una persona', 'osteza' ); ?></button></div>
<form class="chat-form" data-chat-form>
<label class="sr" for="chat-in"><?php esc_html_e( 'Tu mensaje', 'osteza' ); ?></label>
<textarea id="chat-in" rows="1" maxlength="1000" placeholder="<?php esc_attr_e( 'Escribe tu mensaje…', 'osteza' ); ?>" required></textarea>
<button type="submit" aria-label="<?php esc_attr_e( 'Enviar', 'osteza' ); ?>"><?php echo osteza_icon( 'telegram', 20 ); ?></button>
</form>
<?php get_template_part( 'template-parts/data-notice', null, array( 'chat' => true ) ); ?>
</section>
</div>
