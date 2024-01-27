<?php
if ( class_exists( 'LP_Meta_Box_Field' ) ) {
	class Maxcoach_Meta_Box_File_Input_Field extends LP_Meta_Box_Field {

		/**
		 * Constructor.
		 *
		 * @param string $id
		 * @param string $label
		 * @param string $description
		 * @param mixed  $default
		 * @param array  $extra
		 */
		public function __construct( $label = '', $description = '', $default = '', $extra = array() ) {
			parent::__construct( $label, $description, $default, $extra );
		}

		public function output( $thepostid ) {
			if ( empty( $this->id ) ) {
				return;
			}

			$extra = $this->extra;

			$field_class = 'maxcoach-media-input';

			if ( ! empty( $extra['class'] ) ) {
				$field_class .= ' ' . $extra['class'];
			}

			$placeholder   = ! empty( $extra['placeholder'] ) ? $extra['placeholder'] : '';
			$class         = ! empty( $field_class ) ? 'class="' . esc_attr( $field_class ) . '"' : '';
			$style         = ! empty( $extra['style'] ) ? 'style="' . esc_attr( $extra['style'] ) . '"' : '';
			$wrapper_class = ! empty( $extra['wrapper_class'] ) ? esc_attr( $extra['wrapper_class'] ) : '';

			$meta     = $this->meta_value( $thepostid );
			$value    = ! $meta && ! empty( $this->default ) ? $this->default : $meta;
			$value    = isset( $extra['value'] ) ? $extra['value'] : $value;
			$desc_tip = ! empty( $extra['desc_tip'] ) ? $extra['desc_tip'] : '';

			// Custom attribute handling
			$custom_attributes = array();
			if ( ! empty( $extra['custom_attributes'] ) && is_array( $extra['custom_attributes'] ) ) {
				foreach ( $extra['custom_attributes'] as $attribute => $custom_attribute ) {
					$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $custom_attribute ) . '"';
				}
			}

			echo '<div class="form-field maxcoach-media-wrap ' . $this->id . '_field ' . $wrapper_class . '">
		<label for="' . esc_attr( $this->id ) . '">' . wp_kses_post( $this->label ) . '</label>';
			?>
			<div class="short">
			<?php
			echo '<input type="text" ' . $class . ' ' . $style . ' name="' . $this->id . '" id="' . $this->id . '" value="' . $value . '" placeholder="' . esc_attr( $placeholder ) . '" ' . implode(
					' ',
					$custom_attributes
				) . ' /> ';
			?>
			<p>
				<a href="#" class="button button-primary maxcoach-media-upload">
					<?php esc_html_e( 'Select File', 'maxcoach' ); ?>
				</a>
				<a href="#" class="button button-secondary maxcoach-media-remove">
					<?php esc_html_e( 'Remove', 'maxcoach' ); ?>
				</a>
			</p>
			<?php
			if ( ! empty( $this->description ) ) {
				echo '<p class="description">';
				echo '<span>' . wp_kses_post( $this->description ) . '</span>';

				if ( ! empty( $desc_tip ) ) {
					learn_press_quick_tip( $desc_tip );
				}
				echo '</p>';
			}
			echo '</div></div>';
		}

		public function save( $post_id ) {
			$meta_value = isset( $_POST[ $this->id ] ) ? wp_unslash( $_POST[ $this->id ] ) : $this->default;

			update_post_meta( $post_id, $this->id, $meta_value );
		}
	}
}

