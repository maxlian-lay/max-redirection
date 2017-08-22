<?php
	class Mredir_Metabox{
		function __construct(){
			add_action( 'add_meta_boxes', array($this, 'mredir_add_meta_boxes') );
			add_action( 'save_post', array($this, 'mredir_save_meta_box_data') );
		}

		function mredir_add_meta_boxes( $post ){
			add_meta_box( 'mredir_meta_box', 'Redirection Details', array($this, 'mredir_metabox'), 'mredir');
		}

		function mredir_metabox( $post ){
			wp_nonce_field( basename( __FILE__ ), 'mredir_metabox_nonce' );
			$file = fopen(dirname(__DIR__).'/country-list.csv',"r");
			$target_url = get_post_meta( $post->ID, 'target_url', true );
			$country_id = get_post_meta( $post->ID, 'country_id', true );
			?>
				<label for="country_id">Country to redirect: </label>
				<select name="country_id" id="country_id">
					<option value="">Select Country</option>
					<?php
						while(! feof($file)){
							$data = fgetcsv($file);
							if (!empty($country_id)) {
								?>
									<option value="<?php echo $data[4]; ?>" <?php selected( $country_id, $data[4] ); ?> >
										<?php echo $data[5]; ?>
									</option>
								<?php
							}else{
								?>
									<option value="<?php echo $data[4]; ?>">
										<?php echo $data[5]; ?>
									</option>
								<?php
							}
						}
						fclose($file);
					?>
				</select>
				<br/>
				<label for="target_url">Target Url: </label>
				<input name="target_url" id="target_url" class="widefat" value="<?php echo $target_url ?>" placeholder="http://www.example.com"/>
			<?php
		}

		function mredir_save_meta_box_data( $post_id ){
			// verify meta box nonce
			if ( !isset( $_POST['mredir_metabox_nonce'] ) || !wp_verify_nonce( $_POST['mredir_metabox_nonce'], basename( __FILE__ ) ) ){
				return;
			}
			// return if autosave
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
				return;
			}
		  // Check the user's permissions.
			if ( ! current_user_can( 'edit_post', $post_id ) ){
				return;
			}

			if ( isset( $_REQUEST['country_id'] ) ) {
				update_post_meta( $post_id, 'country_id', sanitize_text_field( $_POST['country_id'] ) );
			}
			if ( isset( $_REQUEST['target_url'] ) ) {
				update_post_meta( $post_id, 'target_url', sanitize_text_field( $_POST['target_url'] ) );
			}
		}
	}
	new Mredir_Metabox;