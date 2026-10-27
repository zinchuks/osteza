<?php
/** Brief, factual data-use notice beside the chat and messenger hand-off form. */
$is_chat = ! empty( $args['chat'] );
$email   = sanitize_email( get_theme_mod( 'osteza_email', '' ) );
?>
<div class="privacy-notice <?php echo $is_chat ? 'chat-legal' : 'fpriv'; ?>">
<p><?php esc_html_e( 'El equipo de OSTEZA utiliza tus datos para atender tu consulta y contactarte por el canal que elijas.', 'osteza' ); ?></p>
<details>
<summary><?php esc_html_e( 'Cómo usamos tus datos', 'osteza' ); ?></summary>
<?php if ( $is_chat ) : ?>
<p><?php esc_html_e( 'Tus mensajes se envían a OpenAI para generar las respuestas del asistente y la conversación se guarda en esta web. Si envías una solicitud, sus datos, las fotos y la ubicación que decidas compartir llegan al equipo de OSTEZA a través de Telegram.', 'osteza' ); ?></p>
<p><?php /* translators: %1$d: days without a request; %2$d: days with a request. */ echo esc_html( sprintf( __( 'Las conversaciones de esta web se eliminan automáticamente una vez superados %1$d días desde su creación si no hay solicitud, o %2$d días si hay una solicitud enviada.', 'osteza' ), OSTEZA_CHAT_KEEP, OSTEZA_CHAT_KEEP_LEAD ) ); ?></p>
<p><?php esc_html_e( 'Este borrado automático no elimina las copias en los servicios de mensajería.', 'osteza' ); ?></p>
<?php else : ?>
<p><?php esc_html_e( 'Este formulario prepara un mensaje en tu navegador. No guarda la solicitud en esta web: debes enviarla tú desde WhatsApp o Telegram. El servicio de mensajería que elijas procesa el mensaje según sus propias condiciones.', 'osteza' ); ?></p>
<?php endif; ?>
<?php if ( $email ) : ?>
<p><?php esc_html_e( 'Para solicitar acceso, rectificación o eliminación de tus datos, escribe a:', 'osteza' ); ?> <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
<?php endif; ?>
<?php if ( '#' !== osteza_privacy_url() ) : ?>
<p><a href="<?php echo esc_url( osteza_privacy_url() ); ?>"><?php esc_html_e( 'Política de privacidad', 'osteza' ); ?></a></p>
<?php endif; ?>
</details>
</div>
