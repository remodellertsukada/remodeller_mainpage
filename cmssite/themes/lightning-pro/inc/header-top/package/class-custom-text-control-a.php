<?php
/**
 * Custom Text Control a
 *
 * @package Lightning Pro
 */

/**
 * Custom Text Control A
 */
class Custom_Text_Control_A extends WP_Customize_Control {
	/**
	 * Type
	 *
	 * @var string
	 */
	public $type = 'customtext';

	/**
	 * Decriptions
	 *
	 * @var string
	 */
	public $description = '';

	/**
	 * Render Content.
	 */
	public function render_content() {
		?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<input type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
			<span><?php echo esc_html( $this->description ); ?></span>
		</label>
		<?php
	}
}
