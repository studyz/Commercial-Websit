<?php
if( class_exists( 'WOOCS_STORAGE' ) ) :
?>
    <li class="dropdown menu-item-has-children currency">
        <?php 
		$default = array(
			'USD' => array(
				'name' => 'USD',
				'rate' => 1,
				'symbol' => '&#36;',
				'position' => 'right',
				'is_etalon' => 1,
				'description' => 'USA dollar',
				'hide_cents' => 0,
				'flag' => '',
			),
			'EUR' => array(
				'name' => 'EUR',
				'rate' => 0.89,
				'symbol' => '&euro;',
				'position' => 'left_space',
				'is_etalon' => 0,
				'description' => 'Europian Euro',
				'hide_cents' => 0,
				'flag' => '',
			)
		);
		$current_currency = 'USD';
		$storage = new WOOCS_STORAGE(get_option('woocs_storage', 'session'));
        if( $storage->get_val('woocs_current_currency') != '' ){
            $current_currency = $storage->get_val('woocs_current_currency');
        }
		
		$currencies = get_option('woocs', $default);
        
		?>
        <a  role="button" href="#" data-toggle="dropdown" class="switcher-trigger"><span><?php echo esc_html( $current_currency ); ?></span></a>
        <ul class="dropdown-menu submenu">
            <?php foreach( $currencies as $key=>$currency ) : ?>
                <li class="switcher-option <?php if($key == $current_currency):?> active <?php endif;?>">
                    <a class="woocs_flag_view_item<?php if( $key == $current_currency ): ?> woocs_flag_view_item_current<?php endif; ?>" href="#" data-currency="<?php echo esc_attr( $key ) ?>" >
                        <?php echo esc_html( $currency['name'] ); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>		
    </li>
<?php
endif;
?>