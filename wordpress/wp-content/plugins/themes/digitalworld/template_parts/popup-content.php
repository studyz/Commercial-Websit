<?php
$digitalworld_enable_popup = digitalworld_option('digitalworld_enable_popup','0');
$digitalworld_popup_title = digitalworld_option('digitalworld_popup_title','Newsletter');
$digitalworld_popup_subtitle = digitalworld_option('digitalworld_popup_subtitle','Subscribe to our mailling list to get updates to your email inbox.');

$digitalworld_popup_input_placeholder = digitalworld_option('digitalworld_popup_input_placeholder','Email Address');
$digitalworld_popup_butotn_text = digitalworld_option('digitalworld_popup_button_text','Sign Up');
$digitalworld_poppup_background = digitalworld_option('digitalworld_poppup_background','');
$digitalworld_poppup_socials = digitalworld_option('digitalworld_poppup_socials','');

if( $digitalworld_enable_popup == 0 ) return;
?>
<!--  Popup Newsletter-->
<div class="modal fade" id="popup-newsletter" tabindex="-1" role="dialog" >
    <div class="modal-dialog" role="document">
        <div class="modal-content" <?php if( $digitalworld_poppup_background['url'] ):?> style="background-image: url('<?php echo esc_url( $digitalworld_poppup_background['url']);?>');" <?php endif;?> >
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div class="modal-inner">
                <?php if( $digitalworld_popup_title ):?>
                    <h3 class="title"><?php echo esc_html( $digitalworld_popup_title );?></h3>
                <?php endif;?>
                <?php if( $digitalworld_popup_subtitle ):?>
                    <div class="sub-title"><?php echo esc_html( $digitalworld_popup_subtitle );?></div>
                <?php endif;?>
                <div class="newsletter-form-wrap">
                    <input class="email" type="email" name="email" placeholder="<?php echo esc_html( $digitalworld_popup_input_placeholder );?>">
                    <button type="submit" name="submit_button" class="btn-submit submit-newsletter"><?php echo esc_html( $digitalworld_popup_butotn_text );?></button>
                </div>
                <div class="checkbox btn-checkbox">
                    <label>
                        <input class="digitalworld_disabled_popup_by_user" type="checkbox"><span><?php esc_html_e('Don’t show this popup again','digitalworld');?></span>
                    </label>
                </div>
                <?php if( $digitalworld_poppup_socials && is_array( $digitalworld_poppup_socials ) && count( $digitalworld_poppup_socials ) > 0 ):?>
                    <div class="block-social">
                        <div class="block-title"><?php esc_html_e('Flolow Us','digitalworld');?></div>
                        <div class="block-content">
                            <?php
                            foreach ( $digitalworld_poppup_socials as $social ){
                                digitalworld_social($social);
                            }
                            ?>
                        </div>
                    </div>
                <?php endif;?>
            </div>
        </div>
    </div>
</div><!--  Popup Newsletter-->