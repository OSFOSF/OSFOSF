<?php

namespace Maxcoach_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;

defined( 'ABSPATH' ) || exit;

class Widget_Carousel_Nav_Buttons extends Base {

	public function get_name() {
		return 'tm-carousel-nav-buttons';
	}

	public function get_title() {
		return esc_html__( 'Carousel Nav Buttons', 'maxcoach' );
	}

	public function get_icon_part() {
		return 'eicon-posts-carousel';
	}

	public function get_keywords() {
		return [ 'carousel', 'slider', 'arrows', 'arrow' ];
	}


	protected function register_controls() {
		$this->add_layout_section();
		$this->add_style_button_section();
	}

	private function add_layout_section() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Layout', 'maxcoach' ),
		] );

		$this->add_control( 'style', [
			'label'   => esc_html__( 'Style', 'maxcoach' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'01' => '01',
			],
			'default' => '01',
		] );

		$this->add_responsive_control( 'horizontal_alignment', [
			'label'                => esc_html__( 'Horizontal Alignment', 'maxcoach' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment_full(),
			'default'              => 'left',
			'toggle'               => false,
			'selectors_dictionary' => [
				'left'    => 'flex-start',
				'right'   => 'flex-end',
				'stretch' => 'space-between',
			],
			'selectors'            => [
				'{{WRAPPER}} .button-wrap' => 'justify-content: {{VALUE}}',
			],
		] );

		$this->add_control( 'button_id', [
			'label'       => esc_html__( 'Button ID', 'maxcoach' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => '',
			'title'       => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'maxcoach' ),
			'label_block' => false,
			'description' => wp_kses( __( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'maxcoach' ), 'maxcoach-default' ),
			'separator'   => 'before',
		] );

		$this->add_control( 'icon', [
			'label'       => esc_html__( 'Right Arrow', 'maxcoach' ),
			'type'        => Controls_Manager::ICONS,
			'description' => esc_html__( 'The Left Arrow is the inverse of The Right Arrow.', 'maxcoach' ),
			'default'     => [],
		] );

		$this->end_controls_section();
	}

	private function add_style_button_section() {
		$this->start_controls_section( 'style_button_section', [
			'label' => esc_html__( 'Style', 'maxcoach' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'swiper_arrows_size', [
			'label'      => esc_html__( 'Width', 'maxcoach' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .maxcoach-slider-buttons' => '--button-size: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->add_responsive_control( 'swiper_arrows_icon_size', [
			'label'      => esc_html__( 'Icon Size', 'maxcoach' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 8,
					'max'  => 100,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .maxcoach-slider-buttons' => '--button-icon-size: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->add_responsive_control( 'swiper_arrows_spacing', [
			'label'      => esc_html__( 'Spacing', 'maxcoach' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .maxcoach-slider-buttons' => '--button-gutter: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->add_responsive_control( 'swiper_arrows_border_width', [
			'label'     => esc_html__( 'Border Width', 'maxcoach' ),
			'type'      => Controls_Manager::SLIDER,
			'selectors' => [
				'{{WRAPPER}} .slider-btn' => 'border-width: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->add_responsive_control( 'swiper_arrows_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'maxcoach' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range'      => [
				'%'  => [
					'max'  => 100,
					'step' => 1,
				],
				'px' => [
					'max'  => 200,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .slider-btn' => 'border-radius: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->start_controls_tabs( 'swiper_arrows_style_tabs' );

		$this->start_controls_tab( 'swiper_arrows_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'maxcoach' ),
		] );

		$this->add_control( 'swiper_arrows_text_color', [
			'label'     => esc_html__( 'Color', 'maxcoach' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .maxcoach-slider-buttons' => '--button-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'swiper_arrows_background_color', [
			'label'     => esc_html__( 'Background Color', 'maxcoach' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .maxcoach-slider-buttons' => '--button-background: {{VALUE}};',
			],
		] );

		$this->add_control( 'swiper_arrows_border_color', [
			'label'     => esc_html__( 'Border Color', 'maxcoach' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .maxcoach-slider-buttons' => '--button-border: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'swiper_arrows_box_shadow',
			'selector' => '{{WRAPPER}} .slider-btn',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'swiper_arrows_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'maxcoach' ),
		] );

		$this->add_control( 'swiper_arrows_hover_text_color', [
			'label'     => esc_html__( 'Color', 'maxcoach' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .slider-btn:hover' => '--button-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'swiper_arrows_hover_background_color', [
			'label'     => esc_html__( 'Background Color', 'maxcoach' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .slider-btn:hover' => '--button-background: {{VALUE}};',
			],
		] );

		$this->add_control( 'swiper_arrows_hover_border_color', [
			'label'     => esc_html__( 'Border Color', 'maxcoach' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .slider-btn:hover' => '--button-border: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'swiper_arrows_hover_box_shadow',
			'selector' => '{{WRAPPER}} .slider-btn:hover',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'slider-button', [
			'class'     => 'maxcoach-slider-buttons style-' . $settings['style'],
			'id'        => $settings['button_id'],
			'data-text' => esc_html__( 'Show', 'maxcoach' ) . '&nbsp;',
		] );

		?>
		<div <?php $this->print_render_attribute_string( 'slider-button' ); ?>>
			<div class="button-wrap">
				<div class="slider-btn slider-prev-btn">
					<?php $this->print_nav_icon( $settings, true ); ?>
				</div>
				<div class="slider-btn slider-next-btn">
					<?php $this->print_nav_icon( $settings ); ?>
				</div>
			</div>
		</div>
		<?php
	}

	private function print_default_icon() {
		?>
		<svg width="30" height="20" viewBox="0 0 30 20" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path fill="#fff" d="M29.8015 9.5079L29.8529 9.56783L29.8732 9.59586C29.8852 9.61311 29.8963 9.63079 29.9065 9.64889C29.9362 9.7008 29.9596 9.75698 29.9755 9.81621C29.9822 9.84114 29.9876 9.86615 29.9915 9.89122L29.997 9.93464L30.0001 9.99376L29.9984 10.0511L29.9947 10.0905L29.9914 10.1133C29.9876 10.1384 29.9822 10.1635 29.9755 10.1883C29.9615 10.2404 29.9418 10.29 29.9171 10.3366L29.8963 10.3732L29.8552 10.4337L29.8195 10.477L29.7801 10.5181L29.7202 10.5694L29.6922 10.5898C29.6717 10.604 29.6506 10.6169 29.629 10.6288L29.6074 10.64C25.7078 12.6071 22.7822 15.5327 20.7795 19.469C20.6006 19.8206 20.1706 19.9607 19.819 19.7818C19.4674 19.6029 19.3273 19.1729 19.5062 18.8213C21.2286 15.4359 23.6023 12.7376 26.6379 10.7162L0.714286 10.7166C0.319797 10.7166 0 10.3968 0 10.0023C0 9.62574 0.291385 9.31723 0.660978 9.28997L0.714286 9.28801L26.6376 9.28815C23.6021 7.26679 21.2285 4.56852 19.5062 1.18334C19.3273 0.831737 19.4674 0.401695 19.819 0.222811C20.1706 0.0439271 20.6006 0.183941 20.7795 0.53554C22.7451 4.399 25.5997 7.28878 29.3918 9.25428L29.62 9.37091L29.6566 9.3917L29.7172 9.43283L29.7604 9.46852L29.8015 9.5079Z"/>
		</svg>
		<?php
	}

	private function print_nav_icon( array $settings, $reverse = false ) {
		$classes = [
			'maxcoach-icon',
			'icon',
			'maxcoach-solid-icon',
		];

		$key = uniqid( 'button-icon-' );

		$is_svg = ! isset( $settings['icon']['library'] ) || 'svg' !== $settings['icon']['library'] ? false : true;

		if ( $is_svg ) {
			$classes[] = 'maxcoach-svg-icon';
		}

		if ( $reverse ) {
			$classes[] = 'reverse';
		}

		$this->add_render_attribute( $key, 'class', $classes );
		?>
		<div <?php $this->print_render_attribute_string( $key ); ?>>
			<?php if ( ! empty( $settings['icon']['value'] ) ) : ?>
				<?php $this->render_icon( $settings, $settings['icon'], [ 'aria-hidden' => 'true' ], $is_svg, 'icon' ); ?>
			<?php else : ?>
				<?php $this->print_default_icon(); ?>
			<?php endif ?>
		</div>
		<?php
	}
}
