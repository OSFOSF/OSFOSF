<?php

namespace Maxcoach_Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

defined( 'ABSPATH' ) || exit;

class Widget_Success_Story_Carousel extends Static_Carousel {
	public function get_name() {
		return 'tm-success-story-carousel';
	}

	public function get_title() {
		return esc_html__( 'Success Story Carousel', 'maxcoach' );
	}

	public function get_icon_part() {
		return 'eicon-testimonial-carousel';
	}

	public function get_keywords() {
		return [ 'success story', 'carousel' ];
	}

	protected function register_controls() {
		parent::register_controls();

		$this->add_box_style_section();

		$this->add_content_style_section();

		$this->add_image_style_section();

		$this->update_controls();
	}

	private function update_controls() {
		$this->update_responsive_control( 'swiper_items', [
			'default'        => '1',
			'tablet_default' => '1',
			'mobile_default' => '1',
		] );

		$this->update_responsive_control( 'swiper_gutter', [
			'default' => 30,
		] );

		$this->update_control( 'slides', [
			'title_field' => '{{{ name }}}',
		] );
	}

	private function add_box_style_section() {
		$this->start_controls_section( 'box_style_section', [
			'label' => esc_html__( 'Box', 'maxcoach' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'box_alignment', [
			'label'     => esc_html__( 'Alignment', 'maxcoach' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => Widget_Utils::get_control_options_text_align(),
			'selectors' => [
				'{{WRAPPER}} .swiper-slide' => 'text-align: {{VALUE}}',
			],
		] );

		$this->add_responsive_control( 'box_max_width', [
			'label'      => esc_html__( 'Max Width', 'maxcoach' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%' ],
			'range'      => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1600,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .testimonial-item' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'box_padding', [
			'label'      => esc_html__( 'Padding', 'maxcoach' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .testimonial-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'box',
			'selector' => '{{WRAPPER}} .testimonial-item',
		] );

		$this->end_controls_section();
	}

	private function add_content_style_section() {
		$this->start_controls_section( 'content_style_section', [
			'label' => esc_html__( 'Content', 'maxcoach' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'content_max_width', [
			'label'      => esc_html__( 'Max Width', 'maxcoach' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%' ],
			'range'      => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1600,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .content-wrap' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'content_alignment', [
			'label'                => esc_html__( 'Alignment', 'maxcoach' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .testimonial-main-content' => 'justify-content: {{VALUE}}',
			],
		] );

		$this->add_control( 'content_text_align', [
			'label'        => esc_html__( 'Text Align', 'maxcoach' ),
			'label_block'  => false,
			'type'         => Controls_Manager::CHOOSE,
			'default'      => 'center',
			'options'      => Widget_Utils::get_control_options_text_align(),
			'prefix_class' => 'align-',
			//'render_type'  => 'template',
			'selectors'    => [
				'{{WRAPPER}} .content-wrap' => 'text-align: {{VALUE}};',
			],
		] );

		$this->add_control( 'title_heading', [
			'label'     => esc_html__( 'Title', 'maxcoach' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'maxcoach' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .title' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'label'    => esc_html__( 'Typography', 'maxcoach' ),
			'selector' => '{{WRAPPER}} .title',
		] );

		$this->add_responsive_control( 'title_margin', [
			'label'      => esc_html__( 'Margin', 'maxcoach' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'text_heading', [
			'label'     => esc_html__( 'Text', 'maxcoach' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'text_color', [
			'label'     => esc_html__( 'Color', 'maxcoach' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .text' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'text_typography',
			'label'    => esc_html__( 'Typography', 'maxcoach' ),
			'selector' => '{{WRAPPER}} .text',
		] );

		$this->add_responsive_control( 'text_margin', [
			'label'      => esc_html__( 'Margin', 'maxcoach' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'text_padding', [
			'label'      => esc_html__( 'Padding', 'maxcoach' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'name_heading', [
			'label'     => esc_html__( 'Name', 'maxcoach' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'name_color', [
			'label'     => esc_html__( 'Color', 'maxcoach' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .name' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'name_typography',
			'label'    => esc_html__( 'Typography', 'maxcoach' ),
			'selector' => '{{WRAPPER}} .name',
		] );

		$this->add_control( 'position_heading', [
			'label'     => esc_html__( 'Position', 'maxcoach' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'position_color', [
			'label'     => esc_html__( 'Color', 'maxcoach' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .position' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'position_typography',
			'label'    => esc_html__( 'Typography', 'maxcoach' ),
			'selector' => '{{WRAPPER}} .position',
		] );

		$this->end_controls_section();
	}

	private function add_image_style_section() {
		$this->start_controls_section( 'image_style_section', [
			'label' => esc_html__( 'Image', 'maxcoach' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'image_spacing', [
			'label'     => esc_html__( 'Spacing', 'maxcoach' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 500,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .info' => 'padding-top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'image_size', [
				'label'     => esc_html__( 'Size', 'maxcoach' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 30,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_repeater_controls( Repeater $repeater ) {
		$repeater->add_control( 'image', [
			'label' => esc_html__( 'Image', 'maxcoach' ),
			'type'  => Controls_Manager::MEDIA,
		] );

		$repeater->add_control( 'video_url', [
			'label'       => esc_html__( 'Attach Video Url', 'maxcoach' ),
			'description' => esc_html__( 'Input Youtube video url or Vimeo video url. For e.g: "https://www.youtube.com/watch?v=XHOmBV4js_E"', 'maxcoach' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
		] );

		$repeater->add_control( 'sub_title', [
			'label'       => esc_html__( 'Sub Title', 'maxcoach' ),
			'label_block' => true,
			'type'        => Controls_Manager::TEXT,
		] );

		$repeater->add_control( 'title', [
			'label'       => esc_html__( 'Title', 'maxcoach' ),
			'label_block' => true,
			'type'        => Controls_Manager::TEXT,
		] );

		$repeater->add_control( 'content', [
			'label' => esc_html__( 'Content', 'maxcoach' ),
			'type'  => Controls_Manager::TEXTAREA,
		] );

		$repeater->add_control( 'avatar', [
			'label' => esc_html__( 'Avatar', 'maxcoach' ),
			'type'  => Controls_Manager::MEDIA,
		] );

		$repeater->add_control( 'name', [
			'label'   => esc_html__( 'Name', 'maxcoach' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'John Doe', 'maxcoach' ),
		] );

		$repeater->add_control( 'position', [
			'label'   => esc_html__( 'Position', 'maxcoach' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'CEO', 'maxcoach' ),
		] );
	}

	protected function get_repeater_defaults() {
		$placeholder_image_src = Utils::get_placeholder_image_src();

		return [
			[
				'sub_title' => 'Success story',
				'title'     => '"The best training I\'ve attended in 20 years of sales."',
				'content'   => 'Your sales managers will go into the field feeling energized, excited, and inspired.',
				'name'      => 'Jennifer C.',
				'position'  => 'Sales Manager',
				'image'     => [ 'url' => $placeholder_image_src ],
				'avatar'    => [ 'url' => $placeholder_image_src ],
			],
			[
				'sub_title' => 'Success story',
				'title'     => '"The best training I\'ve attended in 20 years of sales."',
				'content'   => 'Your sales managers will go into the field feeling energized, excited, and inspired.',
				'name'      => 'Jennifer C.',
				'position'  => 'Sales Manager',
				'image'     => [ 'url' => $placeholder_image_src ],
				'avatar'    => [ 'url' => $placeholder_image_src ],
			],
		];
	}

	protected function before_slider() {
		$this->add_render_attribute( self::SLIDER_KEY, 'class', 'success-story-carousel' );
	}

	private function print_image() {
		$slide = $this->get_current_slide();

		if ( empty( $slide['image']['url'] ) ) {
			return;
		}

		$image_tag = 'div';
		$this->add_render_attribute( 'image-wrap', 'class', 'image-wrap' );
		$this->add_render_attribute( 'image', 'class', 'image' );

		if ( ! empty( $slide['video_url'] ) ) {
			$image_tag = 'a';
			$this->add_render_attribute( 'image-wrap', 'class', 'tm-popup-video' );
			$this->add_render_attribute( 'image', 'class', 'video-link maxcoach-box link-secret' );
			$this->add_render_attribute( 'image', 'href', $slide['video_url'] );
		}
		?>
		<div <?php $this->print_render_attribute_string( 'image-wrap' ); ?>>
			<?php printf( '<%1$s %2$s>', $image_tag, $this->get_render_attribute_string( 'image' ) ); ?>
			<?php echo \Maxcoach_Image::get_elementor_attachment( [
				'settings'       => $slide,
				'image_size_key' => 'image_size',
			] ); ?>
			<?php if ( ! empty( $slide['video_url'] ) ) : ?>
				<?php $this->print_video_button(); ?>
			<?php endif; ?>
			<?php printf( '</%1$s>', $image_tag ); ?>
			<svg width="570" height="400" viewBox="0 0 570 400" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M159.5 139.5L570 0V400H0L159.5 139.5Z" fill="#E4ECE2"/>
			</svg>
		</div>
		<?php
	}

	private function print_video_button() {
		?>
		<div class="video-button">
			<div class="video-play video-play-icon">
				<span class="icon"></span>
			</div>
		</div>
		<?php
	}

	private function print_avatar() {
		$slide = $this->get_current_slide();

		if ( empty( $slide['avatar']['url'] ) ) {
			return;
		}
		?>
		<div class="story-author-avatar">
			<?php echo \Maxcoach_Image::get_elementor_attachment( [
				'settings'  => $slide,
				'image_key' => 'avatar',
			] ); ?>
		</div>
		<?php
	}

	private function print_cite() {
		$slide = $this->get_current_slide();

		if ( empty( $slide['name'] ) && empty( $slide['position'] ) ) {
			return;
		}
		?>
		<div class="story-author-cite">
			<?php if ( ! empty( $slide['name'] ) ): ?>
				<h6 class="name"><?php echo esc_html( $slide['name'] ); ?></h6>
			<?php endif; ?>
			<?php if ( ! empty( $slide['position'] ) ): ?>
				<span class="position"><?php echo esc_html( $slide['position'] ); ?></span>
			<?php endif; ?>
		</div>
		<?php
	}

	protected function print_slide() {
		$settings = $this->get_settings_for_display();
		$item_key = $this->get_current_key();
		$slide    = $this->get_current_slide();
		$this->add_render_attribute( $item_key . '-story', [
			'class' => 'story-item',
		] );
		?>
		<div <?php $this->print_attributes_string( $item_key . '-story' ); ?>>
			<div class="row row-xs-center">
				<div class="col-md-6">
					<?php echo $this->print_image(); ?>
				</div>
				<div class="col-md-6 col-lg-5 col-lg-push-1">
					<?php if ( ! empty( $slide['sub_title'] ) ): ?>
						<h4 class="story-sub-title"><?php echo esc_html( $slide['sub_title'] ); ?></h4>
					<?php endif; ?>
					<?php if ( ! empty( $slide['title'] ) ): ?>
						<h3 class="story-title"><?php echo esc_html( $slide['title'] ); ?></h3>
					<?php endif; ?>
					<?php if ( ! empty( $slide['content'] ) ): ?>
						<div class="story-description"><?php echo wp_kses_post( $slide['content'] ); ?></div>
					<?php endif; ?>
					<div class="story-author">
						<?php $this->print_avatar(); ?>
						<?php $this->print_cite();; ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
