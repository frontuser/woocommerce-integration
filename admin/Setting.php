<?php

/**
 * Creates the menu item for the plugin.
 *
 * Registers a new menu item under 'Tools' and uses the dependency passed into
 * the constructor in order to display the page corresponding to this menu item.
 *
 * @package Frontuser_Admin_Setting
 */
class Setting
{
	/**
	 * A reference the class responsible for rendering the menu_page page.
	 *
	 * @var    Setting_Page
	 * @access private
	 */
	private $setting_page;

	/**
	 * Initializes all of the partial classes.
	 *
	 * @param Setting_Page $setting_page A reference to the class that renders the page for the plugin.
	 */
	public function __construct( $setting_page )
	{
		$this->setting_page = $setting_page;
	}

	/**
	 * Adds a menu for this plugin to the 'Tools' menu.
	 */
	public function init()
	{
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
	}

	/**
	 * Creates the menu item and calls on the Setting Page object to render
	 * the actual contents of the page.
	 */
	public function add_menu()
	{
		add_menu_page(
			__( 'Frontuser', 'frontuser' ),
			'Frontuser',
			'manage_options',
			'frontuser',
			array( $this->setting_page, 'render' ),
			FRONTUSER_URL . 'assets/images/favicon.png',
			'59'
		);

		add_submenu_page(
			'frontuser',
			'Setting',
			'Setting',
			'manage_options',
			'frontuser'
		);
	}

	/**
	 * Remove meta-data of frontuser plugin
	 */
	public static function delete()
    {
	    delete_option( 'frontuser_enable' );
	    delete_option( 'frontuser_website_code' );
	    delete_option( 'frontuser_matrix_enable' );
	    delete_option( 'frontuser_product_attribute' );
	    delete_option( 'frontuser_user_attribute' );
    }
}


/**
 * Creates the setting page for the plugin.
 *
 * Provides the functionality necessary for rendering the page corresponding
 * to the submenu with which this page is associated.
 *
 * @package Frontuser_Admin_Settings
 */
class Setting_Page
{
	/**
	 * This function renders the contents of the page associated with the Setting
	 * that invokes the render method. In the context of this plugin, this is the
	 * Setting class.
	 */
	public function render()
	{
		// Save settings if data has been posted
		$message = '';
		if ( ! empty( $_POST ) ) {
		    update_option( 'frontuser_enable', !empty($_POST['frontuser_enable'])?$_POST['frontuser_enable']:'' );
		    update_option( 'frontuser_website_code', !empty($_POST['frontuser_website_code'])?$_POST['frontuser_website_code']:'' );
			update_option( 'frontuser_matrix_enable', !empty($_POST['frontuser_matrix_enable'])?$_POST['frontuser_matrix_enable']:'' );

			$message = '<div id="message" class="updated inline"><p><strong>Your settings have been saved.</strong></p></div>';
		}

		?>
		<div class="wrap">
			<h1>Frontuser Settings</h1>
            <?php echo $message; ?>
			<form method="post" action="<?php echo admin_url("admin.php?page=frontuser"); ?>">
				<?php settings_fields( 'my-cool-plugin-settings-group' ); ?>
				<?php do_settings_sections( 'my-cool-plugin-settings-group' ); ?>
				<table class="form-table">

                    <tr valign="top">
                        <th scope="row">Enable/Disable:</th>
                        <td>
                            <select name="frontuser_enable" style="min-width: 215px;">
								<?php
								foreach ( array('1' => 'Enable', '0' => 'Disable') as $key => $val ) {
									?>
                                    <option value="<?php echo esc_attr( $key ); ?>" <?php selected( esc_attr(get_option('frontuser_enable')), $key ); ?>>
										<?php echo $val ?>
                                    </option>
									<?php
								}
								?>
                            </select>
                        </td>
                    </tr>

					<tr valign="top">
						<th scope="row">Website Hash:</th>
						<td><input type="text" name="frontuser_website_code" value="<?php echo esc_attr(get_option('frontuser_website_code')); ?>"/></td>
					</tr>

					<tr valign="top">
						<th scope="row">Matrix Variable Feature:</th>
						<td>
                            <select name="frontuser_matrix_enable" style="min-width: 215px;">
								<?php
                                    foreach ( array('yes' => 'Yes', 'no' => 'No') as $key => $val ) {
                                        ?>
                                            <option value="<?php echo esc_attr( $key ); ?>" <?php selected( esc_attr(get_option('frontuser_matrix_enable')), $key ); ?>>
                                                <?php echo $val ?>
                                            </option>
                                        <?php
                                    }
								?>
                            </select>
                        </td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}