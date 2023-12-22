<?php

namespace GutenbergBlocksDisabler;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class AdminPage {

	/**
	 * Default options when plugin is installed for the first time.
	 *
	 * @var array
	 */
	private $options = array(
		'core/archives',
		'core/categories',
		'core/latest-comments',
		'core/latest-posts',
		'core/search',
		'core/social-link',
		'core/social-links',
		'core/button',
		'core/buttons',
		'core/column',
		'core/columns',
		'core/group',
		'core/separator',
		'core/spacer',
		'core/heading',
		'core/list',
		'core/list-item',
		'core/paragraph',
		'core/quote',
		'core/table',
		'core/cover',
		'core/file',
		'core/gallery',
		'core/image',
		'core/media-text',
		'core/video',
	);

	/**
	 * Constructor.
	 *
	 * @param array $options
	 */
	public function __construct( array $options ) {
		if ( $options ) {
			$this->options = $options;
		}
	}

	/**
	 * Register the src page class with all the appropriate WordPress hooks.
	 *
	 * @param array $options
	 */
	public static function register( array $options ) {
		$page = new self( $options );

		add_action( 'admin_menu', array( $page, 'addAdminPage' ) );
		add_action( 'admin_init', array( $page, 'saveSettings' ) );
	}

	/**
	 * Adds the src page to the menu.
	 */
	public function addAdminPage() {
		add_options_page( GBD_PLUGIN_NAME, GBD_PLUGIN_NAME, 'install_plugins', GBD_PLUGIN_SLUG, array( $this, 'render' ) );
	}

	/**
	 * Renders the src page using the Settings API.
	 */
	public function render() {
		$wp_block_type_registry = \WP_Block_Type_Registry::get_instance();

		$blocks_grouped_by_category = array();

		foreach ( $wp_block_type_registry->get_all_registered() as $block_type_name => $block_type ) {
				$category = $block_type->category;

			if ( ! array_key_exists( $category, $blocks_grouped_by_category ) ) {
				$blocks_grouped_by_category[ $category ] = array();
			}

			$blocks_grouped_by_category[ $category ][] = $block_type_name;
		}

		uasort(
			$blocks_grouped_by_category,
			function ( $a, $b ) {
				return count( $b ) - count( $a );
			}
		);
		?>
		<div class="wrap" id="gbd-admin">
			<h2><?php echo GBD_PLUGIN_NAME; ?></h2>
			<form action="options.php" method="POST">
			<?php foreach ( $blocks_grouped_by_category as $category => $blocks ) : ?>
			<div class="gbd-form-section">
				<h2><?php echo esc_html( $category ); ?></h2>
				<?php foreach ( $blocks as $block ) : ?>
				<div class="gbd-form-field">
					<input id="<?php echo esc_attr( $block ); ?>" <?php checked( in_array( $block, $this->options ) ); ?> type="checkbox" name="<?php echo esc_attr( $block ); ?>" />
					<label for="<?php echo esc_attr( $block ); ?>">
							<?php echo esc_html( $block ); ?>
					</label>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endforeach; ?>
			<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Save the selected block types into database.
	 */
	public function saveSettings() {
		if ( isset( $_POST['submit'] ) ) {
			unset( $_POST['submit'] );
			foreach ( $_POST as $block => $value ) {
				$_POST[ $block ] = sanitize_text_field( $value );
			}

			update_option( GBD_PLUGIN_SLUG, array_keys( $_POST ) );
			wp_safe_redirect( admin_url( 'options-general.php?page=' . GBD_PLUGIN_SLUG . '&settings-update=true' ) );
			exit;
		}
	}
}