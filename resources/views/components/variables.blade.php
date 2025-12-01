<style>
    :root {
        --container-width: {{ $customizes['container_width'] ?? '1400' }}px;
        --global-padding: {{ $customizes['global_padding'] ?? '16' }}px;
        --arzavo-background: #ffffff;
        --arzavo-border-color: #d4d4d4;
        --arzavo-shadow-color: #000000;
        --arzavo-shadow-spread: {{ $customizes['shadow_spread'] ?? '2px' }};
        --arzavo-shadow-blur: {{ $customizes['shadow_blur'] ?? '10px' }};
        --arzavo-border-width: {{ $customizes['border_width'] ?? '1px' }};
        --arzavo-border-radius: {{ $customizes['border_radius']  ?? '0px' }};
        --arzavo-heading-font-family: {{ $customizes['heading_font_family'] ?? '' }};

        --arzavo-heading-color: #000000;

        /* ========================= */
        /*           H1              */
        /* ========================= */
        --arzavo-heading-1-font-size: {{ $customizes['heading_1_font_size'] ?? '40' }}px;
        --arzavo-heading-1-font-size-tablet: {{ $customizes['heading_1_font_size_tablet'] ?? '32' }}px;
        --arzavo-heading-1-font-size-mobile: {{ $customizes['heading_1_font_size_mobile'] ?? '26' }}px;
        --arzavo-heading-1-line-height: {{ $customizes['heading_1_line_height'] ?? '1.1' }};
        --arzavo-heading-1-text-transform: {{ $customizes['heading_1_text_transform'] ?? 'default' }};
        --arzavo-heading-1-font-weight: {{ $customizes['heading_1_text_weight'] ?? 'bold' }};

        /* ========================= */
        /*           H2              */
        /* ========================= */
        --arzavo-heading-2-font-size: {{ $customizes['heading_2_font_size'] ?? '36' }}px;
        --arzavo-heading-2-font-size-tablet: {{ $customizes['heading_2_font_size_tablet'] ?? '30' }}px;
        --arzavo-heading-2-font-size-mobile: {{ $customizes['heading_2_font_size_mobile'] ?? '24' }}px;
        --arzavo-heading-2-line-height: {{ $customizes['heading_2_line_height'] ?? '1.1' }};
        --arzavo-heading-2-text-transform: {{ $customizes['heading_2_text_transform'] ?? 'default' }};
        --arzavo-heading-2-font-weight: {{ $customizes['heading_2_text_weight'] ?? 'bold' }};

        /* ========================= */
        /*           H3              */
        /* ========================= */
        --arzavo-heading-3-font-size: {{ $customizes['heading_3_font_size'] ?? '32' }}px;
        --arzavo-heading-3-font-size-tablet: {{ $customizes['heading_3_font_size_tablet'] ?? '26' }}px;
        --arzavo-heading-3-font-size-mobile: {{ $customizes['heading_3_font_size_mobile'] ?? '22' }}px;
        --arzavo-heading-3-line-height: {{ $customizes['heading_3_line_height'] ?? '1.1' }};
        --arzavo-heading-3-text-transform: {{ $customizes['heading_3_text_transform'] ?? 'default' }};
        --arzavo-heading-3-font-weight: {{ $customizes['heading_3_text_weight'] ?? 'bold' }};

        /* ========================= */
        /*           H4              */
        /* ========================= */
        --arzavo-heading-4-font-size: {{ $customizes['heading_4_font_size'] ?? '28' }}px;
        --arzavo-heading-4-font-size-tablet: {{ $customizes['heading_4_font_size_tablet'] ?? '24' }}px;
        --arzavo-heading-4-font-size-mobile: {{ $customizes['heading_4_font_size_mobile'] ?? '20' }}px;
        --arzavo-heading-4-line-height: {{ $customizes['heading_4_line_height'] ?? '1.1' }};
        --arzavo-heading-4-text-transform: {{ $customizes['heading_4_text_transform'] ?? 'default' }};
        --arzavo-heading-4-font-weight: {{ $customizes['heading_4_text_weight'] ?? 'bold' }};

        /* ========================= */
        /*           H5              */
        /* ========================= */
        --arzavo-heading-5-font-size: {{ $customizes['heading_5_font_size'] ?? '24' }}px;
        --arzavo-heading-5-font-size-tablet: {{ $customizes['heading_5_font_size_tablet'] ?? '20' }}px;
        --arzavo-heading-5-font-size-mobile: {{ $customizes['heading_5_font_size_mobile'] ?? '18' }}px;
        --arzavo-heading-5-line-height: {{ $customizes['heading_5_line_height'] ?? '1.1' }};
        --arzavo-heading-5-text-transform: {{ $customizes['heading_5_text_transform'] ?? 'default' }};
        --arzavo-heading-5-font-weight: {{ $customizes['heading_5_text_weight'] ?? 'bold' }};

        /* ========================= */
        /*           H6              */
        /* ========================= */
        --arzavo-heading-6-font-size: {{ $customizes['heading_6_font_size'] ?? '20' }}px;
        --arzavo-heading-6-font-size-tablet: {{ $customizes['heading_6_font_size_tablet'] ?? '18' }}px;
        --arzavo-heading-6-font-size-mobile: {{ $customizes['heading_6_font_size_mobile'] ?? '16' }}px;
        --arzavo-heading-6-line-height: {{ $customizes['heading_6_line_height'] ?? '1.1' }};
        --arzavo-heading-6-text-transform: {{ $customizes['heading_6_text_transform'] ?? 'default' }};
        --arzavo-heading-6-font-weight: {{ $customizes['heading_6_text_weight'] ?? 'bold' }};


        
        --arzavo-paragraph-color: #525252;
        --arzavo-paragraph-font-size: {{ $customizes['paragraph_font_size'] ?? '16' }}px;
        --arzavo-paragraph-text-transform: {{ $customizes['paragraph_text_transform'] ?? 'default' }};
        --arzavo-paragraph-font-weight: {{ $customizes['paragraph_text_weight'] ?? 'normal' }};
        
        --arzavo-secondary-text-color: #858585;
        --arzavo-secondary-text-font-size: {{ $customizes['secondary_text_font_size'] ?? '12' }}px;
        --arzavo-secondary-text-text-transform: {{ $customizes['secondary_text_text_transform'] ?? 'default' }};
        --arzavo-secondary-text-font-weight: {{ $customizes['secondary_text_font_weight'] ?? 'normal' }};

        --arzavo-primary-btn-background: #000000;
        --arzavo-primary-btn-text-color: #ffffff;
        --arzavo-primary-btn-hover-background: #ffffff;
        --arzavo-primary-btn-hover-text-color: #000000;
        --arzavo-primary-btn-border-color: #000000;
        --arzavo-primary-btn-hover-border: #000000;
        --arzavo-primary-btn-size: {{ $customizes['primary_button_size'] ?? '8px 12px' }};
        --arzavo-primary-btn-font-size: {{ $customizes['primary_button_font_size'] ?? '16px' }};
        --arzavo-primary-btn-shape: {{ $customizes['primary_button_shape'] ?? ($customizes['border_radius'] ?? '0') }};
        --arzavo-primary-btn-border-width: {{ $customizes['primary_button_border_width'] ?? '0' }};
        --arzavo-primary-btn-font-family: {{ $customizes['primary_button_font_family'] ?? 'Outfit' }};
        --arzavo-primary-btn-font-weight: {{ $customizes['primary_button_font_weight'] ?? 'bold' }};
        --arzavo-primary-btn-text-transform: {{ $customizes['primary_button_text_transform'] ?? 'default' }};
        --arzavo-secondary-btn-background: #f3f4f6;
        --arzavo-secondary-btn-text-color: #000000;
        --arzavo-secondary-btn-hover-background: #000000;
        --arzavo-secondary-btn-hover-text-color: #ffffff;
        --arzavo-secondary-btn-border-color: #000000;
        --arzavo-secondary-btn-hover-border: #000000;
        --arzavo-secondary-btn-size: {{ $customizes['secondary_button_size'] ?? '8px 12px' }};
        --arzavo-secondary-btn-font-size: {{ $customizes['secondary_button_font_size'] ?? '16px' }};
        --arzavo-secondary-btn-shape: {{ $customizes['secondary_button_shape'] ?? ($customizes['border_radius'] ?? '0') }};
        --arzavo-secondary-btn-border-width: {{ $customizes['secondary_button_border_width'] ?? '0' }};
        --arzavo-secondary-btn-font-family: {{ $customizes['secondary_button_font_family'] ?? 'Outfit' }};
        --arzavo-secondary-btn-font-weight: {{ $customizes['secondary_button_font_weight'] ?? 'normal' }};
        --arzavo-secondary-btn-text-transform: {{ $customizes['secondary_button_text_transform'] ?? 'default' }};

        --arzavo-link-color: #1d4ed8;
        --arzavo-link-hover-color: #2563eb;
        --arzavo-link-btn-text-color: #1d4ed8;
        --arzavo-link-btn-hover-text-color: #2563eb;
        --arzavo-link-btn-font-size: {{ $customizes['link_button_font_size'] ?? '16px' }};
        --arzavo-link-btn-font-family: {{ $customizes['link_button_font_family'] ?? 'Outfit' }};
        --arzavo-link-btn-font-weight: {{ $customizes['link_button_font_weight'] ?? 'normal' }};
        --arzavo-link-btn-text-transform: {{ $customizes['link_button_text_transform'] ?? 'default' }};
        --arzavo-border-invert: #333333;


    }

    .dark-mode {
        --arzavo-background: #121212;
        --arzavo-bg-primary: #1e293b;
        --arzavo-bg-secondary: #344358;
        --arzavo-bg-tertiary: #3a3a3a;
        --arzavo-bg-invert: #ffffff;
        --arzavo-bg-invert-secondary: #e2e2e2;
        --arzavo-text-primary: #f9fafb;
        --arzavo-text-secondary: #a3a3a3;
        --arzavo-text-tertiary: #313131;
        --arzavo-text-invert: #111827;
        --arzavo-text-invert-secondary: #111827;
        --arzavo-border-primary: #374151;
        --arzavo-border-invert: #d1d5db;
        --arzavo-shadow-color: rgba(0, 0, 0, 0.5);
        --arzavo-shadow-weighted: 0 4px 6px -1px var(--arzavo-shadow-color),
            0 2px 4px -1px var(--arzavo-shadow-color);
    }
</style>