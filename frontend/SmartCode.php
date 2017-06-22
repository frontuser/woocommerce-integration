<?php

class FrontuserSmartCode
{

	/**
	 * SmartCode constructor.
	 */
	function __construct()
	{

	}

	/**
	 * Outputs the given setting
	 *
	 * @return String
	 * @since 1.0
	 */
	public static function render()
	{
		$enable = get_option( 'frontuser_enable', 0 );
		$webhash = get_option( 'frontuser_website_code', '' );

		if(!empty( $enable) && $enable == 1 && !empty( $webhash)) {

			echo "
				<script type=\"text/javascript\">
					(function(p,u,s,h){
						var t='$webhash';
						p._fq=p._fq||[];
						p._fq.push(['_currentTime',Date.now()]);
						s=u.createElement('script');
						s.type='text/javascript';
						s.async=true;
						s.src='https://cdn.frontuser.com/sdk/1.0/fuser-'+t+'.js',
						h=u.getElementsByTagName('script')[0];
						h.parentNode.insertBefore(s,h);
					})(window,document);
				</script>
			";
		}
	}


	public static function matrix()
	{
		global $post, $product, $wp_query, $wp;

		$matrix_data = array();
		if(!empty($post) && $post instanceof WP_Post) {
			$matrix_data['page'] = array(
                'name'  => $post->post_title,
                'type'  => $post->post_name,
                'url'   => get_permalink($post),
                'status'=> $post->post_status,
                'type'  => $post->post_type,
                'created_on'  => $post->post_date,
                'updated_on'  => $post->post_modified,
			);
		}

		$user = wp_get_current_user();
		if(!empty($user) && $user instanceof WP_User) {
			if(!empty($user->to_array())) {

				$matrix_data['user'] = array(
					'id'    => $user->__get( 'ID' ),
					'name'  => $user->__get( 'display_name' ),
					'email' => $user->__get( 'user_email' )
				);

				$user_attributes = json_decode( get_option('frontuser_user_attribute', '{}' ), true);
				if(!empty( $user_attributes )) {
					foreach ($user_attributes as $key => $field) {
						$value = $user->get( $field );
						if(!empty( $value )) {
							$matrix_data['user'][$key] = $value;
						}
					}
				}
			}
		}

		if(is_home()) {
			$matrix_data['referrer'] = array(
				'host' => $_SERVER['HTTP_HOST'],
				'path' => $_SERVER['REQUEST_URI'],
				'search' => $_SERVER['QUERY_STRING'],
				'utm' => array(
					'medium' => !empty($_REQUEST['medium'])?$_REQUEST['medium']:'',
					'source' => !empty($_REQUEST['source'])?$_REQUEST['source']:'',
					'campaign' => !empty($_REQUEST['campaign'])?$_REQUEST['campaign']:'',
				)
			);
		}

		if(frontuser_is_woocommerce_enabled() && is_product_category()) {
			$category = $wp_query->get_queried_object();
			$matrix_data['category'] = array(
				"id" => $category->term_id,
                "name" =>  $category->name,
			);

			$args = array(
				'posts_per_page' => -1,
				'tax_query' => array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'product_cat',
						'field' => 'slug',
						'terms' => $category->slug
					)
				),
				'post_type' => 'product',
				'orderby' => 'title,'
			);
			$products = new WP_Query( $args );
			$count = 0;
			while ( $products->have_posts() ) {
				$products->the_post();
				global $product;
				$count++;
				$matrix_data['category']['listing']['items'][] = array(
					'pid' => $product->get_id(),
					'sku' => $product->get_sku(),
					'name' => $product->get_name(),
					'stock' => $product->get_stock_quantity(),
					'currency' => get_option('woocommerce_currency'),
					'unit_price' => $product->get_regular_price(),
					'final_price' => $product->get_price()
				);
			}
			$matrix_data['category']['listing']['items_count'] = $count;
		}

		if(frontuser_is_woocommerce_enabled() && is_product()) {
			if(!empty( $product) && $product instanceof WC_Product) {

				$matrix_data['product'] = array(
					'id' => $product->get_id(),
					'sku' => $product->get_sku(),
					'name' => $product->get_name(),
					'description' => $product->get_description(),
					'cat_id' => $product->get_category_ids(),
					'stock' => $product->get_stock_quantity(),
					'currency' => get_option('woocommerce_currency'),
					'unit_price' => $product->get_regular_price(),
					'final_price' => $product->get_price()
				);

				$productdata = $product->get_data();
				$product_attributes = json_decode( get_option('frontuser_product_attribute', '{}' ), true);
				if(!empty( $product_attributes )) {
					foreach ($product_attributes as $key => $field) {
						if(!empty( $productdata[$field] )) {
							$matrix_data['product'][$key] = $productdata[$field];
						}
					}
				}

				$attributes = $product->get_attributes();
				if (!empty( $attributes )) {
					foreach ($attributes as $key => $attribute) {
						$terms = $attribute->get_terms();
						if(!empty( $terms )) {
							foreach ($terms as $term) {
								$matrix_data['product']['attributes'][$key][] = $term->name;
							}
						}
					}
				}

				$related_products = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), 5, $product->get_upsell_ids() ) ), 'wc_products_array_filter_visible' );
				if(!empty( $related_products)) {
					foreach ($related_products as $related_product) {
						$matrix_data['product']['related_products'][] = array(
							'pid' => $related_product->get_id(),
							'sku' => $related_product->get_sku(),
							'name' => $related_product->get_name(),
							'url_path' => $related_product->get_permalink(),
							'stock' => $related_product->get_stock_quantity(),
							'currency' => get_option('woocommerce_currency'),
							'unit_price' => $related_product->get_regular_price(),
							'final_price' => $related_product->get_price()
						);
					}
				}

				if($product->get_reviews_allowed()) {
					$args = array ('post_id' => $product->get_id(), 'post_type' => 'product', 'status' => 'approve',);
					$comments = get_comments( $args );
					if(!empty( $comments)) {
						foreach ($comments as $comment) {
							$matrix_data['product']['reviews'][] = array(
								'comment' => $comment->comment_content,
								'rating' => intval( get_comment_meta($comment->comment_ID, 'rating', true ))
							);
						}
					}
				}
			}
		}

		if(frontuser_is_woocommerce_enabled() && is_cart()) {
			$cart = WC()->cart;
			if(!empty( $cart ) && $cart instanceof WC_Cart) {

				$chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
				$matrix_data['cart'] = array(
					"items_qty" => $cart->get_cart_contents_count(),
					"currency" => get_option('woocommerce_currency'),
					"subtotal" => self::filteramount( $cart->get_cart_subtotal() ),
					"tax_amount" => self::filteramount( $cart->get_taxes_total()),
					"shipping_method" => !empty( $chosen_methods[0])?$chosen_methods[0]:'',
					"shipping_amount" => self::filteramount( $cart->get_cart_shipping_total()),
					"coupon_code" => $cart->applied_coupons,
					"discount_amount" => self::filteramount( $cart->get_total_discount()),
					"created_on" => date( 'Y-m-d H:i:s'),
					"updated_on" => date( 'Y-m-d H:i:s'),
					"grand_total" => self::filteramount( $cart->get_total())
				);

				if(!empty( $cart->cart_contents)) {
					foreach($cart->cart_contents as $item) {
						$matrix_data['cart']['cart_items'][] = array(
				            "pid" => $item['data']->get_id(),
				            "sku" => $item['data']->get_sku(),
				            "name" => $item['data']->get_name(),
				            "currency" => get_option('woocommerce_currency'),
				            "unit_price" => $item['data']->get_regular_price(),
				            "final_price" => $item['data']->get_price(),
				            "qty_added" => $item['quantity'],
				            "row_total" => $item['line_total'],
				            "discount_amount" => $item['data']->get_regular_price() - $item['data']->get_price(),
				            "created_on" => $item['data']->get_date_created()->date('Y-m-d H:i:s'),
				            "updated_on" => $item['data']->get_date_modified()->date('Y-m-d H:i:s')
						);
					}
				}
			}
		}

		if(frontuser_is_woocommerce_enabled() && is_order_received_page()) {

			$order_id = absint( $wp->query_vars['order-received'] );
			if ( $order_id > 0 ) {
				$order = wc_get_order( $order_id );
				$orderdata = $order->get_data();
				$shipping = current( $orderdata['shipping_lines']);

				$matrix_data['order_success'] = array(
					"order_id" => $orderdata['id'],
					"items_qty" => 0,
					"currency" => $orderdata['currency'],
					"subtotal" => $order->get_subtotal(),
					"tax_amount" => $orderdata['total_tax'],
					"shipping_method" => $shipping->get_name(),
					"shipping_amount" => $orderdata['shipping_total'],
					"payment_method" => $orderdata['payment_method'],
					"coupon_code" => $order->get_used_coupons(),
					"discount_amount" => $orderdata['discount_total'],
					"grand_total" => $orderdata['total'],
					"created_on" => $order->get_date_created()->date('Y-m-d H:i:s'),
					"updated_on" => $order->get_date_modified()->date('Y-m-d H:i:s')
				);

				$matrix_data['order_success']['addresses']['billing'] = array(
					"name" => $order->get_billing_first_name(). " ".$order->get_billing_last_name(),
		            "address_1" => $order->get_billing_address_1(),
		            "address_2" => $order->get_billing_address_2(),
		            "city" => $order->get_billing_city(),
		            "region" => $order->get_billing_state(),
		            "country" => WC()->countries->countries[ $order->get_billing_country() ],
		            "country_code" => $order->get_billing_country(),
		            "zipcode" => $order->get_billing_postcode()
				);

				$matrix_data['order_success']['addresses']['shipping'] = array(
					"name" => $order->get_shipping_first_name(). " ".$order->get_shipping_last_name(),
					"address_1" => $order->get_shipping_address_1(),
					"address_2" => $order->get_shipping_address_2(),
					"city" => $order->get_shipping_city(),
					"region" => $order->get_shipping_state(),
					"country" => WC()->countries->countries[ $order->get_shipping_country() ],
					"country_code" => $order->get_shipping_country(),
					"zipcode" => $order->get_shipping_postcode()
				);

				$items = $orderdata['line_items'];
				if(!empty( $items)) {
					foreach ($items as $item) {

						$itemdata = $item->get_data();
						$product = $item->get_product();

						$matrix_data['order_success']['ordered_items'][] = array(
							"pid" => $product->get_id(),
							"sku" => $product->get_sku(),
							"name" => $product->get_name(),
							"currency" => get_option('woocommerce_currency'),
							"unit_price" => $product->get_regular_price(),
							"final_price" => $product->get_price(),
							"qty_added" => $itemdata['quantity'],
							"row_total" => $itemdata['total'],
							"discount_amount" => 0,
							"created_on" => $product->get_date_created()->date('Y-m-d H:i:s'),
							"updated_on" => $product->get_date_modified()->date('Y-m-d H:i:s')
						);

						$matrix_data['order_success']['items_qty'] += $itemdata['quantity'];
					}
				}
			}
		}

		$matrix_data = json_encode($matrix_data);

		echo "
			<script type=\"text/javascript\">
				window.fu_matrix = $matrix_data;
			</script>
		";
	}


	public static function filteramount($amount = 0)
	{
		$amount = strip_tags($amount);
		$amount = preg_replace("/&#?[a-z0-9]+;/i","", strip_tags($amount));
		$amount = floatval( $amount );

		return $amount;
	}
}