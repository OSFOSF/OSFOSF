<?php

namespace Maxcoach_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

class Widget_Course_List extends Posts_Base {

	public function get_name() {
		return 'tm-course-list';
	}

	public function get_title() {
		return esc_html__( 'Course List', 'maxcoach' );
	}

	public function get_icon_part() {
		return 'eicon-post-list';
	}

	public function get_keywords() {
		return [ 'course' ];
	}

	protected function get_post_type() {
		return 'lp_course';
	}

	protected function get_post_category() {
		return 'course_category';
	}

	protected function get_query_author_object() {
		return Module_Query_Base::QUERY_OBJECT_USER;
	}

	public function get_script_depends() {
		return [
			'maxcoach-grid-query',
			'maxcoach-widget-grid-post',
		];
	}

	protected function register_controls() {
		$this->add_layout_section();

		$this->add_box_style_section();

		$this->add_thumbnail_style_section();

		$this->add_caption_style_section();

		parent::register_controls();
	}

	private function add_layout_section() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Layout', 'maxcoach' ),
		] );

		$this->add_control( 'hover_effect', [
			'label'        => esc_html__( 'Hover Effect', 'maxcoach' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				''         => esc_html__( 'None', 'maxcoach' ),
				'zoom-in'  => esc_html__( 'Zoom In', 'maxcoach' ),
				'zoom-out' => esc_html__( 'Zoom Out', 'maxcoach' ),
			],
			'default'      => '',
			'prefix_class' => 'maxcoach-animation-',
		] );

		$this->add_control( 'show_caption_instructor', [
			'label'     => esc_html__( 'Instructor', 'maxcoach' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_on'  => esc_html__( 'Show', 'maxcoach' ),
			'label_off' => esc_html__( 'Hide', 'maxcoach' ),
		] );

		$this->add_control( 'show_caption_date', [
			'label'     => esc_html__( 'Date', 'maxcoach' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_on'  => esc_html__( 'Show', 'maxcoach' ),
			'label_off' => esc_html__( 'Hide', 'maxcoach' ),
		] );

		$this->add_control( 'show_caption_meta', [
			'label'       => esc_html__( 'Meta', 'maxcoach' ),
			'label_block' => true,
			'type'        => Controls_Manager::SELECT2,
			'multiple'    => true,
			'default'     => [
				'lessons',
				'students',
			],
			'options'     => [
				'lessons'  => esc_html__( 'Lessons', 'maxcoach' ),
				'students' => esc_html__( 'Students', 'maxcoach' ),
				'duration' => esc_html__( 'Duration', 'maxcoach' ),
				'category' => esc_html__( 'Category', 'maxcoach' ),
			],
		] );

		$this->add_control( 'show_caption_meta_icon', [
			'label'        => esc_html__( 'Meta Icon', 'maxcoach' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => esc_html__( 'Show', 'maxcoach' ),
			'label_off'    => esc_html__( 'Hide', 'maxcoach' ),
			'prefix_class' => 'course-caption-meta-icon-',
		] );

		$this->add_control( 'show_caption_excerpt', [
			'label'     => esc_html__( 'Excerpt', 'maxcoach' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_on'  => esc_html__( 'Show', 'maxcoach' ),
			'label_off' => esc_html__( 'Hide', 'maxcoach' ),
		] );

		$this->add_control( 'excerpt_length', [
			'label'     => esc_html__( 'Excerpt Length', 'maxcoach' ),
			'type'      => Controls_Manager::NUMBER,
			'min'       => 5,
			'condition' => [
				'show_caption_excerpt' => 'yes',
			],
		] );

		$this->add_control( 'show_caption_buttons', [
			'label'     => esc_html__( 'Buttons', 'maxcoach' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_on'  => esc_html__( 'Show', 'maxcoach' ),
			'label_off' => esc_html__( 'Hide', 'maxcoach' ),
		] );

		$this->add_control( 'purchase_button_text', [
			'label'       => esc_html__( 'Purchase Button', 'maxcoach' ),
			'label_block' => true,
			'description' => esc_html__( 'Custom text for purchase button.', 'maxcoach' ),
			'type'        => Controls_Manager::TEXT,
			'condition'   => [
				'show_caption_buttons' => 'yes',
			],
		] );

		$this->add_control( 'thumbnail_default_size', [
			'label'        => esc_html__( 'Use Default Thumbnail Size', 'maxcoach' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => '1',
			'return_value' => '1',
			'separator'    => 'before',
		] );

		$this->end_controls_section();
	}

	private function add_box_style_section() {
		$this->start_controls_section( 'box_style_section', [
			'label' => esc_html__( 'Box', 'maxcoach' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'box_border_radius', [
			'label'     => esc_html__( 'Border Radius', 'maxcoach' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .maxcoach-box' => 'border-radius: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->start_controls_tabs( 'box_style_tabs' );

		$this->start_controls_tab( 'box_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'maxcoach' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'box_background',
			'selector' => '{{WRAPPER}} .maxcoach-box',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_box_shadow',
			'selector' => '{{WRAPPER}} .maxcoach-box',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'box_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'maxcoach' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'box_hover_background',
			'selector' => '{{WRAPPER}} .maxcoach-box:hover',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_hover_box_shadow',
			'selector' => '{{WRAPPER}} .maxcoach-box:hover',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_thumbnail_style_section() {
		$this->start_controls_section( 'thumbnail_style_section', [
			'label' => esc_html__( 'Thumbnail', 'maxcoach' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'thumbnail_border_radius', [
			'label'     => esc_html__( 'Border Radius', 'maxcoach' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .maxcoach-image, {{WRAPPER}} .maxcoach-image img' => 'border-radius: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->start_controls_tabs( 'thumbnail_effects_tabs' );

		$this->start_controls_tab( 'thumbnail_effects_normal_tab', [
			'label' => esc_html__( 'Normal', 'maxcoach' ),
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'thumbnail_box_shadow',
			'selector' => '{{WRAPPER}} .maxcoach-image',
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'css_filters',
			'selector' => '{{WRAPPER}} .maxcoach-image img',
		] );

		$this->add_control( 'thumbnail_opacity', [
			'label'     => esc_html__( 'Opacity', 'maxcoach' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'max'  => 1,
					'min'  => 0.10,
					'step' => 0.01,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .maxcoach-image img' => 'opacity: {{SIZE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'thumbnail_effects_hover_tab', [
			'label' => esc_html__( 'Hover', 'maxcoach' ),
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'thumbnail_box_shadow_hover',
			'selector' => '{{WRAPPER}} .maxcoach-box:hover .maxcoach-image',
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'css_filters_hover',
			'selector' => '{{WRAPPER}} .maxcoach-box:hover .maxcoach-image img',
		] );

		$this->add_control( 'thumbnail_opacity_hover', [
			'label'     => esc_html__( 'Opacity', 'maxcoach' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'max'  => 1,
					'min'  => 0.10,
					'step' => 0.01,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .maxcoach-box:hover .maxcoach-image img' => 'opacity: {{SIZE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_caption_style_section() {
		$this->start_controls_section( 'caption_style_section', [
			'label'     => esc_html__( 'Caption', 'maxcoach' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'show_caption' => 'yes',
			],
		] );

		$this->add_responsive_control( 'caption_text_align', [
			'label'     => esc_html__( 'Alignment', 'maxcoach' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => Widget_Utils::get_control_options_text_align(),
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .course-info' => 'text-align: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'caption_padding', [
			'label'      => esc_html__( 'Padding', 'maxcoach' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .course-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'course_title_style_hr', [
			'label'     => esc_html__( 'Course Title', 'maxcoach' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'course_title_typography',
			'label'    => esc_html__( 'Typography', 'maxcoach' ),
			'selector' => '{{WRAPPER}} .course-info .course-title',
		] );

		$this->add_control( 'course_date_style_hr', [
			'label'     => esc_html__( 'Course Date', 'maxcoach' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_caption_date' => 'yes',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'course_date_typography',
			'label'     => esc_html__( 'Typography', 'maxcoach' ),
			'selector'  => '{{WRAPPER}} .course-info .course-date',
			'condition' => [
				'show_caption_date' => 'yes',
			],
		] );

		$this->add_responsive_control( 'course_date_margin', [
			'label'      => esc_html__( 'Margin', 'maxcoach' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .course-info .course-date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'show_caption_date' => 'yes',
			],
		] );

		$this->add_control( 'course_meta_style_hr', [
			'label'     => esc_html__( 'Course Meta', 'maxcoach' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'course_meta_typography',
			'label'    => esc_html__( 'Typography', 'maxcoach' ),
			'selector' => '{{WRAPPER}} .course-info .course-meta',
		] );

		$this->add_responsive_control( 'course_meta_margin', [
			'label'      => esc_html__( 'Margin', 'maxcoach' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .course-info .course-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'course_instructor_style_hr', [
			'label'     => esc_html__( 'Course Instructor', 'maxcoach' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_caption_instructor' => 'yes',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'course_instructor_typography',
			'label'     => esc_html__( 'Typography', 'maxcoach' ),
			'selector'  => '{{WRAPPER}} .course-info .course-instructor',
			'condition' => [
				'show_caption_instructor' => 'yes',
			],
		] );

		$this->add_responsive_control( 'course_instructor_margin', [
			'label'      => esc_html__( 'Margin', 'maxcoach' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .course-info .course-instructor' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'show_caption_instructor' => 'yes',
			],
		] );

		$this->add_control( 'course_price_style_hr', [
			'label'     => esc_html__( 'Course Price', 'maxcoach' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'course_price_typography',
			'label'    => esc_html__( 'Typography', 'maxcoach' ),
			'selector' => '{{WRAPPER}} .course-info .course-price',
		] );

		$this->add_responsive_control( 'course_price_margin', [
			'label'      => esc_html__( 'Margin', 'maxcoach' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .course-info .course-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'course_excerpt_style_hr', [
			'label'     => esc_html__( 'Course Excerpt', 'maxcoach' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_caption_excerpt' => 'yes',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'course_excerpt_typography',
			'label'     => esc_html__( 'Typography', 'maxcoach' ),
			'selector'  => '{{WRAPPER}} .course-info .course-excerpt',
			'condition' => [
				'show_caption_excerpt' => 'yes',
			],
		] );

		$this->add_responsive_control( 'course_excerpt_margin', [
			'label'      => esc_html__( 'Margin', 'maxcoach' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .course-info .course-excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'show_caption_excerpt' => 'yes',
			],
		] );

		$this->add_control( 'caption_colors_hr', [
			'label'     => esc_html__( 'Colors', 'maxcoach' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->start_controls_tabs( 'caption_colors_tabs' );

		$this->start_controls_tab( 'caption_colors_normal_tab', [
			'label' => esc_html__( 'Normal', 'maxcoach' ),
		] );

		$this->add_control( 'course_title_color', [
			'label'     => esc_html__( 'Title', 'maxcoach' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .course-info .course-title' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'caption_date_color', [
			'label'     => esc_html__( 'Date', 'maxcoach' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .course-info .course-date' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_caption_date' => 'yes',
			],
		] );

		$this->add_control( 'caption_meta_color', [
			'label'     => esc_html__( 'Meta', 'maxcoach' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .course-info .course-meta' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'caption_instructor_color', [
			'label'     => esc_html__( 'Instructor', 'maxcoach' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .course-info .course-instructor' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_caption_instructor' => 'yes',
			],
		] );

		$this->add_control( 'caption_price_color', [
			'label'     => esc_html__( 'Price', 'maxcoach' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .course-info .course-price' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'caption_excerpt_color', [
			'label'     => esc_html__( 'Excerpt', 'maxcoach' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .course-info .course-excerpt' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_caption_excerpt' => 'yes',
			],
		] );

		$this->add_control( 'caption_line_hr', [
			'label'     => esc_html__( 'Caption Line', 'maxcoach' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'caption_style' => [ '13' ],
			],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => 'box_caption_line',
			'selector'  => '{{WRAPPER}} .course-info:after',
			'condition' => [
				'caption_style' => [ '13' ],
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'caption_colors_hover_tab', [
			'label' => esc_html__( 'Hover', 'maxcoach' ),
		] );

		$this->add_control( 'course_title_hover_color', [
			'label'     => esc_html__( 'Title', 'maxcoach' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .course-info .course-title:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'course_date_hover_color', [
			'label'     => esc_html__( 'Date', 'maxcoach' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .course-wrapper:hover .course-info .course-date' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_caption_date' => 'yes',
			],
		] );

		$this->add_control( 'course_meta_hover_color', [
			'label'     => esc_html__( 'Meta', 'maxcoach' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .course-wrapper .course-info .course-meta a:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'course_instructor_hover_color', [
			'label'     => esc_html__( 'Instructor', 'maxcoach' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .course-wrapper:hover .course-info .course-instructor' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_caption_instructor' => 'yes',
			],
		] );

		$this->add_control( 'course_price_hover_color', [
			'label'     => esc_html__( 'Price', 'maxcoach' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .course-wrapper:hover .course-info .course-price' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'caption_excerpt_hover_color', [
			'label'     => esc_html__( 'Excerpt', 'maxcoach' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .course-wrapper:hover .course-info .course-excerpt' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_caption_excerpt' => 'yes',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->query_posts();
		/**
		 * @var $query \WP_Query
		 */
		$query     = $this->get_query();
		$post_type = $this->get_post_type();

		$this->add_render_attribute( 'wrapper', 'class', [
			'maxcoach-course-list',
		] );
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<?php if ( $query->have_posts() ) : ?>
				<?php
				learn_press_setup_user();

				if ( 'yes' === $settings['show_caption_buttons'] && ! empty( $settings['purchase_button_text'] ) ) {
					add_filter( 'learn-press/purchase-course-button-text', [
						$this,
						'change_purchase_button_text',
					] );
				}

				set_query_var( 'maxcoach_query', $query );
				set_query_var( 'settings', $settings );

				get_template_part( 'loop/widgets/course-list/style', '01' );

				remove_filter( 'learn-press/purchase-course-button-text', [
					$this,
					'change_purchase_button_text',
				] );
				?>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
		</div>
		<?php
	}

	public function change_purchase_button_text( $text ) {
		$settings = $this->get_settings_for_display();

		$text = $settings['purchase_button_text'];

		return $text;
	}
}
