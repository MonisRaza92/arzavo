<style>
    :root {
        --container-width: {{ $customizes['container_width'] ?? '1400' }}px;
        --global-padding: {{ $customizes['global_padding'] ?? '16' }}px;
        --arzavo-border-width: {{ $customizes['border_width'] ?? '1px' }};
        --arzavo-border-radius: {{ $customizes['border_radius']  ?? '0' }};
        
        
        --arz-desktop-logo-size: {{ $customizes['logo_height_desktop'] ?? 40 }}px;
        --arz-mobile-logo-size: {{ $customizes['logo_height_mobile'] ?? 40 }}px;
        
        /* ========================= */
        /*      Colors & Fonts       */
        /* ========================= */
        --arz-heading: #000000;
        --arz-bg: #ffffff;
        --arz-subheading: #000000;
        --arz-paragraph: #525252;
        --arz-body-text: #858585;
        --arz-border: #d4d4d4;
        --arz-link: #1d4ed8;
        --arz-link-hover: #2563eb;
        --arz-heading-font: {{ $customizes['heading_font_family'] ?? '' }};
        --arz-paragraph-font: {{ $customizes['paragraph_font_family'] ?? '' }};
        --arz-body-text-font: {{ $customizes['body_text_font_family'] ?? '' }};
        --arz-link-font: {{ $customizes['link_font_family'] ?? 'Outfit' }};

        /* ========================= */
        /*           H1              */
        /* ========================= */
        --arz-h1-size: {{ $customizes['heading_1_font_size'] ?? '40' }}px;
        --arz-h1-line: {{ $customizes['heading_1_line_height'] ?? '1.1' }};
        --arz-h1-transform: {{ $customizes['heading_1_text_transform'] ?? 'default' }};
        --arz-h1-weight: {{ $customizes['heading_1_text_weight'] ?? 'bold' }};
        
        /* ========================= */
        /*           H2              */
        /* ========================= */
        --arz-h2-size: {{ $customizes['heading_2_font_size'] ?? '36' }}px;
        --arz-h2-line: {{ $customizes['heading_2_line_height'] ?? '1.1' }};
        --arz-h2-transform: {{ $customizes['heading_2_text_transform'] ?? 'default' }};
        --arz-h2-weight: {{ $customizes['heading_2_text_weight'] ?? 'bold' }};

        /* ========================= */
        /*           H3              */
        /* ========================= */
        --arz-h3-size: {{ $customizes['heading_3_font_size'] ?? '32' }}px;
        --arz-h3-line: {{ $customizes['heading_3_line_height'] ?? '1.1' }};
        --arz-h3-transform: {{ $customizes['heading_3_text_transform'] ?? 'default' }};
        --arz-h3-weight: {{ $customizes['heading_3_text_weight'] ?? 'bold' }};
        
        /* ========================= */
        /*           H4              */
        /* ========================= */
        --arz-h4-size: {{ $customizes['heading_4_font_size'] ?? '28' }}px;
        --arz-h4-line: {{ $customizes['heading_4_line_height'] ?? '1.1' }};
        --arz-h4-transform: {{ $customizes['heading_4_text_transform'] ?? 'default' }};
        --arz-h4-weight: {{ $customizes['heading_4_text_weight'] ?? 'bold' }};

        /* ========================= */
        /*           H5              */
        /* ========================= */
        --arz-h5-size: {{ $customizes['heading_5_font_size'] ?? '24' }}px;
        --arz-h5-line: {{ $customizes['heading_5_line_height'] ?? '1.1' }};
        --arz-h5-transform: {{ $customizes['heading_5_text_transform'] ?? 'default' }};
        --arz-h5-weight: {{ $customizes['heading_5_text_weight'] ?? 'bold' }};

        /* ========================= */
        /*           H6              */
        /* ========================= */
        --arz-h6-size: {{ $customizes['heading_6_font_size'] ?? '20' }}px;
        --arz-h6-line: {{ $customizes['heading_6_line_height'] ?? '1.1' }};
        --arz-h6-transform: {{ $customizes['heading_6_text_transform'] ?? 'default' }};
        --arz-h6-weight: {{ $customizes['heading_6_text_weight'] ?? 'bold' }};


        /* ========================= */
        /*        Paragraph          */
        /* ========================= */
        --arz-paragraph-size: {{ $customizes['paragraph_font_size'] ?? '16' }}px;
        --arz-paragraph-transform: {{ $customizes['paragraph_text_transform'] ?? 'default' }};
        --arz-paragraph-weight: {{ $customizes['paragraph_text_weight'] ?? 'normal' }};
        

        /* ========================= */
        /*        Body Text          */
        /* ========================= */
        --arz-body-text-size: {{ $customizes['body_text_font_size'] ?? '12' }}px;
        --arz-body-text-transform: {{ $customizes['body_text_text_transform'] ?? 'default' }};
        --arz-body-text-weight: {{ $customizes['body_text_font_weight'] ?? 'normal' }};

        /* ========================= */
        /*       Primary Btn         */
        /* ========================= */

        --arz-btn-bg: #000000;
        --arz-btn-text: #ffffff;
        --arz-btn-hover-bg: #ffffff;
        --arz-btn-hover-text: #000000;
        --arz-btn-border: #000000;
        --arz-btn-hover-border: #000000;

        --arz-btn-py: {{ $customizes['primary_button_padding_vertical'] ?? '12' }}px;
        --arz-btn-px: {{ $customizes['primary_button_padding_horizontal'] ?? '12' }}px;

        --arz-btn-size: {{ $customizes['primary_button_font_size'] ?? '16' }}px;
        --arz-btn-radius: {{ $customizes['primary_button_shape'] ?? ($customizes['border_radius'] ?? '0') }}px;
        --arz-btn-border-w: {{ $customizes['primary_button_border_width'] ?? '0' }}px;

        --arz-btn-font: {{ $customizes['primary_button_font_family'] ?? 'Outfit' }};
        --arz-btn-weight: {{ $customizes['primary_button_font_weight'] ?? 'bold' }};
        --arz-btn-transform: {{ $customizes['primary_button_text_transform'] ?? 'default' }};



        /* ========================= */
        /*      Secondary Btn        */
        /* ========================= */

        --arz-btn2-bg: #f3f4f6;
        --arz-btn2-text: #000000;
        --arz-btn2-hover-bg: #000000;
        --arz-btn2-hover-text: #ffffff;
        --arz-btn2-border: #000000;
        --arz-btn2-hover-border: #000000;

        --arz-btn2-py: {{ $customizes['secondary_button_padding_vertical'] ?? '12' }}px;
        --arz-btn2-px: {{ $customizes['secondary_button_padding_horizontal'] ?? '12' }}px;

        --arz-btn2-size: {{ $customizes['secondary_button_font_size'] ?? '16' }}px;
        --arz-btn2-radius: {{ $customizes['secondary_button_shape'] ?? ($customizes['border_radius'] ?? '0') }}px;
        --arz-btn2-border-w: {{ $customizes['secondary_button_border_width'] ?? '0' }}px;

        --arz-btn2-font: {{ $customizes['secondary_button_font_family'] ?? 'Outfit' }};
        --arz-btn2-weight: {{ $customizes['secondary_button_font_weight'] ?? 'normal' }};
        --arz-btn2-transform: {{ $customizes['secondary_button_text_transform'] ?? 'default' }};



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
