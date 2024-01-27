<?php
$section  = 'settings_preset';
$priority = 1;
$prefix   = 'settings_preset_';

Maxcoach_Kirki::add_field( 'theme', array(
	'type'     => 'preset',
	'settings' => 'settings_preset',
	'label'    => esc_html__( 'Settings Preset', 'maxcoach' ),
	'section'  => $section,
	'default'  => '-1',
	'priority' => $priority++,
	'multiple' => 0,
	'choices'  => array(
		'-1'             => array(
			'label'    => esc_html__( 'None', 'maxcoach' ),
			'settings' => [],
		),
		'rtl'            => array(
			'label'    => 'RTL',
			'settings' => [
				'typography_body' => [
					'font-family'    => 'Cairo',
					'variant'        => '400',
					'font-size'      => '15px',
					'line-height'    => '1.74',
					'letter-spacing' => '0em',
				],
			],
		),
		'sales-coaching' => array(
			'label'    => 'Sales Coaching',
			'settings' => [
				'typography_body'                                 => [
					'font-family'    => 'Satoshi',
					'variant'        => '500',
					'font-size'      => '15px',
					'line-height'    => '1.74',
					'letter-spacing' => '0em',
				],
				'header_style_15_navigation_typography'           => [
					'font-family'    => 'Satoshi',
					'variant'        => '700',
					'font-size'      => '16px',
					'line-height'    => '26px',
					'letter-spacing' => '',
					'text-transform' => 'none',
				],
				'header_style_15_search_enable'                   => '0',
				'header_style_15_button_text'                     => 'Let’s talk',
				'header_style_15_button_link'                     => '#',
				'header_style_15_dark_button_custom_color'        => [
					'color'      => '#fff',
					'background' => '#103724',
					'border'     => '#103724',
				],
				'header_style_15_dark_button_hover_custom_color'  => [
					'color'      => '#fff',
					'background' => '#85A17F',
					'border'     => '#85A17F',
				],
				'header_sticky_navigation_link_color'             => [
					'normal' => '#111',
					'hover'  => '#BB441E',
				],
				'primary_color'                                   => '#85A17F',
				'secondary_color'                                 => '#103724',
				'button_color'                                    => [
					'color'      => '#fff',
					'background' => '#103724',
					'border'     => '#103724',
				],
				'button_hover_color'                              => [
					'color'      => '#fff',
					'background' => '#85A17F',
					'border'     => '#85A17F',
				],
				'navigation_dropdown_border_bottom_color'         => '#85A17F',
				'navigation_minimal_01_item_hover_color'          => '#85A17F',
				'navigation_minimal_01_dropdown_link_hover_color' => '#85A17F',
				'pre_loader_shape_color'                          => '#85A17F',
				'link_color'                                      => [
					'normal' => '#696969',
					'hover'  => '#85A17F',
				],
				'top_bar_style_01_link_color'                     => [
					'hover' => '#85A17F',
				],
				'header_style_15_dark_navigation_link_color'      => [
					'normal' => Maxcoach::HEADING_COLOR,
					'hover'  => '#85A17F',
				],
				'header_style_15_dark_header_icon_color'          => [
					'normal' => Maxcoach::HEADING_COLOR,
					'hover'  => '#85A17F',
				],
				'form_input_focus_color'                          => [
					'color'      => '#777',
					'background' => '#fff',
					'border'     => '#85A17F',
				],
			],
		),
		'mental-therapy' => array(
			'label'    => 'Sales Coaching',
			'settings' => [
				'typography_body'                                => [
					'font-family'    => 'Satoshi',
					'variant'        => '500',
					'font-size'      => '15px',
					'line-height'    => '1.74',
					'letter-spacing' => '0em',
				],
				'header_style_15_navigation_typography'          => [
					'font-family'    => 'Satoshi',
					'variant'        => '700',
					'font-size'      => '16px',
					'line-height'    => '26px',
					'letter-spacing' => '',
					'text-transform' => 'none',
				],
				'header_style_15_search_enable'                  => '0',
				'header_style_15_button_text'                    => 'Let’s talk',
				'header_style_15_button_link'                    => '#',
				'header_style_15_dark_button_custom_color'       => [
					'color'      => '#fff',
					'background' => '#BB441E',
					'border'     => '#BB441E',
				],
				'header_style_15_dark_button_hover_custom_color' => [
					'color'      => '#fff',
					'background' => '#E28666',
					'border'     => '#E28666',
				],
				'header_style_15_dark_navigation_link_color'     => [
					'normal' => Maxcoach::HEADING_COLOR,
					'hover'  => '#BB441E',
				],
				'header_style_15_dark_header_icon_color'         => [
					'normal' => Maxcoach::HEADING_COLOR,
					'hover'  => '#BB441E',
				],
				'header_sticky_navigation_link_color'            => [
					'normal' => '#111',
					'hover'  => '#BB441E',
				],
				'primary_color'                                  => '#BB441E',
				'secondary_color'                                => '#333',
				'link_color'                                     => [
					'normal' => '#696969',
					'hover'  => '#BB441E',
				],
				'button_color'                                   => [
					'color'      => '#fff',
					'background' => '#103724',
					'border'     => '#103724',
				],
				'button_hover_color'                             => [
					'color'      => '#fff',
					'background' => '#BB441E',
					'border'     => '#BB441E',
				],
				'navigation_dropdown_border_bottom_color'        => '#BB441E',
			],
		),
	),
) );
