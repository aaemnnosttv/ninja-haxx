<?php
/**
 * Plugin Name: Ninja Haxx
 * Description: A plugin for ninja hackery.
 * Version: 1.0
 */

add_action(
	'plugins_loaded',
	function () {
		remove_action( 'admin_notices', 'companion_admin_notices' );
	}
);

add_action(
	'wp_dashboard_setup',
	function () {
		if ( ! function_exists( 'companion_admin_notices' ) ) {
			return;
		}

		wp_add_dashboard_widget(
			'jurassic-ninja',
			'Welcome to Jurassic Ninja!',
			function () {
				$password_option_key = 'jurassic_ninja_admin_password';
				$sysuser_option_key = 'jurassic_ninja_sysuser';
				$admin_password = is_multisite() ? get_blog_option( 1, $password_option_key ) : get_option( $password_option_key );
				$sysuser = is_multisite() ? get_blog_option( 1, $sysuser_option_key ) : get_option( $sysuser_option_key );
				$host = parse_url( network_site_url(), PHP_URL_HOST );

				?>
				<p>
					<strong><code id="jurassic_url" class="jurassic_ninja_field"><?php echo esc_html( network_site_url() ); ?></code></strong>
					<?php echo esc_html__( 'will be destroyed in 7 days. Sign out and sign in to get 7 more days.' ); ?>
				</p>

				<table class="jurassic_ninja_table">
					<tbody>
					<tr>
						<th class="alignright">WP User</th>
						<td><code id="jurassic_username" class="jurassic_ninja_field">demo</code></td>
					</tr>
					<tr>
						<th class="alignright">WP/SSH Password</th>
						<td><code id="jurassic_password" class="jurassic_ninja_field"
						          data-copy-text="<?php echo esc_attr( $admin_password ); ?>"
							><?php echo str_repeat( '&bull;', 16 ) ?></code>
						</td>
					</tr>
					<tr>
						<th class="alignright">System User</th>
						<td><code class="jurassic_ninja_field"><?php echo esc_html( $sysuser ); ?></td>
					</tr>
					<tr>
						<th class="alignright">System Hostname</th>
						<td><code class="jurassic_ninja_field"><?php echo esc_html( $host ); ?></td>
					</tr>
					<tr>
						<th class="alignright">System Path</th>
						<td><code class="jurassic_ninja_field"><?php echo esc_html( get_home_path() ); ?></code></td>
					</tr>
					<tr>
						<th class="alignright">SSH Command</th>
						<td><code class="jurassic_ninja_field"><?php echo esc_html( "ssh $sysuser@$host" ); ?></code></td>
					</tr>
					</tbody>
				</table>

				<style type="text/css">
					.jurassic_ninja_welcome img {
						margin: 0 5px 0 0;
						max-width: 40px;
					}
					.jurassic_ninja_field {
						user-select: all;
						cursor: copy;
						text-overflow: ellipsis;
						white-space: nowrap;
					}
					.jurassic_ninja_table {
						display: block;
						overflow-x: auto;
					}
					.jurassic_ninja_table th {
						white-space: nowrap;
					}
				</style>
				<script>
					/**
					 * Helper to copy-paste credential fields in notice
					 */
					function jurassic_ninja_clippy( str ) {
						var el = document.createElement( 'input' );
						el.value = str;
						document.body.appendChild( el );
						el.select();
						document.execCommand( 'copy' );
						document.body.removeChild( el );
					};

					var jurassic_ninja_fields = document.getElementsByClassName( 'jurassic_ninja_field' );

					// IE11 compatible way to loop this
					// https://developer.mozilla.org/en-US/docs/Web/API/NodeList#Example
					Array.prototype.forEach.call( jurassic_ninja_fields, function ( field ) {
						field.addEventListener( 'click', function( e ) {
						    var copyText = e.target.dataset.copyText || e.target.innerText;
							jurassic_ninja_clippy( copyText );
						} );
					} );
				</script>
				<?php
			}
		);
	}
);