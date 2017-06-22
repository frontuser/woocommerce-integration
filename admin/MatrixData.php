<?php

/**
 * Creates the menu item for the plugin.
 *
 * Registers a new menu item under 'Tools' and uses the dependency passed into
 * the constructor in order to display the page corresponding to this menu item.
 *
 * @package Frontuser_Admin_Setting
 */
class FrontuserMatrixData
{
	/**
	 * A reference the class responsible for rendering the menu_page page.
	 *
	 * @var    MatrixData_Page
	 * @access private
	 */
	private $matrixdata_page;

	/**
	 * Initializes all of the partial classes.
	 *
	 * @param MatrixData_Page $matrixdata_page A reference to the class that renders the page for the plugin.
	 */
	public function __construct( $matrixdata_page )
	{
		$this->matrixdata_page = $matrixdata_page;
	}

	/**
	 * Adds a menu for this plugin to the 'Tools' menu.
	 */
	public function init()
	{
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
	}

	/**
	 * Creates the menu item and calls on the MatrixData Page object to render
	 * the actual contents of the page.
	 */
	public function add_menu()
	{
		add_submenu_page(
			'frontuser',
			'Matrix Data',
			'Matrix Data',
			'manage_options',
			'matrixdata',
			array( $this->matrixdata_page, 'render' )
		);
	}
}


/**
 * Creates the matrixdata page for the plugin.
 *
 * Provides the functionality necessary for rendering the page corresponding
 * to the submenu with which this page is associated.
 *
 * @package Frontuser_Admin_MatrixDatas
 */
class FrontuserMatrixData_Page
{

	/**
	 * MatrixData_Page constructor.
	 */
	function __construct()
	{
	    wp_enqueue_style(
			'frontuser_admin_styles',
			FRONTUSER_URL . 'assets/css/admin.css',
			false,
			FRONTUSER_VERSION
		);

		wp_enqueue_script(
            'frontuser_admin_script',
			FRONTUSER_URL . 'assets/js/general.js',
            false,
			FRONTUSER_VERSION
        );
	}


	/**
	 * This function renders the contents of the page associated with the MatrixData
	 * that invokes the render method. In the context of this plugin, this is the
	 * MatrixData class.
	 */
	public function render()
	{
		$message = '';
		if ( ! empty( $_POST ) ) {

			$product_attributes = ''; $user_attributes = '';
			if(!empty( $_POST['product_attribute_name'] )) {
                $count = count($_POST['product_attribute_name']);
                for($i = 0; $i < $count; $i++) {
                    $key = $_POST['product_attribute_name'][$i];
                    $value = $_POST['product_attribute_values'][$i];
                    if(!empty( $key) && !empty( $value)) {
	                    $product_attributes[$key] = $value;
                    }
                }
			}
			if(!empty( $_POST['user_attribute_name'] )) {
				$count = count($_POST['user_attribute_name']);
				for($i = 0; $i < $count; $i++) {
					$key = $_POST['user_attribute_name'][$i];
					$value = $_POST['user_attribute_values'][$i];
					if(!empty( $key) && !empty( $value)) {
						$user_attributes[$key] = $value;
					}
				}
			}

			update_option('frontuser_product_attribute', json_encode($product_attributes) );
			update_option('frontuser_user_attribute', json_encode($user_attributes) );
			$message = '<div id="message" class="updated inline"><p><strong>Product and user attributes have been saved.</strong></p></div>';
		}

		$product_attributes = json_decode( get_option('frontuser_product_attribute', '{}' ), true);
		$user_attributes = json_decode( get_option('frontuser_user_attribute', '{}' ), true);

		?>
		<div class="wrap">
			<h1>Matrix Data</h1>
			<?php echo $message; ?>
			<?php include( FRONTUSER_PATH . 'views/matrix-data-panel.php' ); ?>
		</div>
		<?php
	}
}