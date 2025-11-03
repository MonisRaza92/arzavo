<style>
    :root {
        --container-width: {{ $customizes['container_width'] ?? '1400' }}px;
        --global-padding: {{ $customizes['global_padding'] ?? '16' }}px;
        --arzaq-bg-primary: {{ $customizes['primary_color'] ?? '#ffffff' }};
        --arzaq-bg-secondary: {{ $customizes['secondary_color'] ?? '#ebebeb' }};
        --arzaq-bg-accent: {{ $customizes['accent_color'] ?? '#000000' }};
        --arzaq-bg-tertiary: {{ $customizes['tetiary_color'] ?? '#757575' }};
        --arzaq-border-color: {{ $customizes['border_color'] ?? '#d4d4d4d' }};
        --arzaq-shadow-color: {{ $customizes['shadow_color'] ?? '#000000' }};
        --arzaq-heading-color: {{ $customizes['heading_color'] ?? '#000000' }};
        --arzaq-heading-font-family: {{ $customizes['heading_font_family'] ?? '' }};
        --arzaq-heading-font-size: {{ $customizes['heading_font_size'] ?? '40' }}px;
        --arzaq-heading-line-height: {{ $customizes['heading_line_height'] ?? '1.1' }};
        --arzaq-heading-text-transform: {{ $customizes['heading_text_transform'] ?? 'default' }};
        --arzaq-heading-font-weight: {{ $customizes['heading_text_weight'] ?? 'bold' }};
        --arzaq-subheading-color: {{ $customizes['subheading_color'] ?? '#212121' }};
        --arzaq-subheading-font-size: {{ $customizes['subheading_font_size'] ?? '20' }}px;
        --arzaq-subheading-text-transform: {{ $customizes['subheading_text_transform'] ?? 'default' }};
        --arzaq-subheading-font-weight: {{ $customizes['subheading_text_weight'] ?? 'normal' }};
        --arzaq-paragraph-color: {{ $customizes['pragaraph_color'] ?? '#525252' }};
        --arzaq-paragraph-font-size: {{ $customizes['paragraph_font_size'] ?? '16' }}px;
        --arzaq-paragraph-text-transform: {{ $customizes['paragraph_text_transform'] ?? 'default' }};
        --arzaq-paragraph-font-weight: {{ $customizes['paragraph_text_weight'] ?? 'normal' }};
        --arzaq-secondary-text-color: {{ $customizes['secondary_text_color'] ?? '#858585' }};
        --arzaq-secondary-text-font-size: {{ $customizes['body_font_size'] ?? '12' }}px;
        --arzaq-secondary-text-text-transform: {{ $customizes['body_text_transform'] ?? 'default' }};
        --arzaq-secondary-text-font-weight: {{ $customizes['body_text_weight'] ?? 'normal' }};
        --arzaq-primary-btn-background: {{ $customizes['primary_btn_background'] ?? '#000000' }};
        --arzaq-primary-btn-text-color: {{ $customizes['primary_btn_text_color'] ?? '#ffffff' }};
        --arzaq-primary-btn-hover-background: {{ $customizes['primary_btn_hover_background'] ?? '#ffffff' }};
        --arzaq-primary-btn-hover-text-color: {{ $customizes['primary_btn_hover_text_color'] ?? '#000000' }};
        --arzaq-primary-btn-border-color: {{ $customizes['primary_btnr_border_color'] ?? '#000000' }};
        --arzaq-primary-btn-hover-border: {{ $customizes['primary_btn_hover_border'] ?? '#000000' }};
        --arzaq-primary-btn-border-radius: {{ $customizes['button_border_radius'] ?? ($customizes['border_radius'] ?? '0') }}px;
        --arzaq-primary-btn-border-width: {{ $customizes['button_border_width'] ?? '1' }}px;
        --arzaq-primary-btn-font-family: {{ $customizes['button_font_family'] ?? 'Outfit' }};
        --arzaq-primary-btn-font-weight: {{ $customizes['button_font_weight'] ?? 'bold' }};
        --arzaq-primary-btn-font-size: {{ $customizes['button_font_size'] ?? '16' }}px;
        --arzaq-primary-btn-text-transform: {{ $customizes['button_text_transform'] ?? 'default' }};
        --arzaq-primary-btn-padding: {{ $customizes['button_padding'] ?? '12' }}px;
        --arzaq-secondary-btn-background: {{ $customizes['secondary_btn_background'] ?? '' }};
        --arzaq-secondary-btn-text-color: {{ $customizes['secondary_btn_text_color'] ?? '#000000' }};
        --arzaq-secondary-btn-hover-background: {{ $customizes['secondary_btn_hover_background'] ?? '#000000' }};
        --arzaq-secondary-btn-hover-text-color: {{ $customizes['secondary_btn_hover_text_color'] ?? '#ffffff' }};
        --arzaq-secondary-btn-border-color: {{ $customizes['secondary_btn_border_color'] ?? '#000000' }};
        --arzaq-secondary-btn-hover-border: {{ $customizes['secondary_btn_hover_border'] ?? '#000000' }};
        --arzaq-secondary-btn-border-radius: {{ $customizes['button_border_radius'] ?? ($customizes['border_radius'] ?? '0') }}px;
        --arzaq-secondary-btn-border-width: {{ $customizes['button_border_width'] ?? '1' }}px;
        --arzaq-secondary-btn-font-family: {{ $customizes['button_font_family'] ?? 'Outfit' }};
        --arzaq-secondary-btn-font-weight: {{ $customizes['button_font_weight'] ?? 'bold' }};
        --arzaq-secondary-btn-font-size: {{ $customizes['button_font_size'] ?? '16' }}px;
        --arzaq-secondary-btn-text-transform: {{ $customizes['button_text_transform'] ?? 'default' }};
        --arzaq-secondary-btn-padding: {{ $customizes['button_padding'] ?? '12' }}px;
        --arzaq-border-width: {{ $customizes['border_width'] ?? '1' }}px;
        --arzaq-border-radius: {{ $customizes['border_radius']  ?? '0' }}px;
        --arzaq-font-family: {{ $customizes['font_family'] ?? 'Outfit' }};
        --arzaq-border-invert: #333333;
        --arzaq-shadow-color: rgba(0, 0, 0, 0.1);
        --arzaq-shadow-weighted: 0 4px 6px -1px var(--arzaq-shadow-color),
            0 2px 4px -1px var(--arzaq-shadow-color);


    }

    .dark-mode {
        --arzaq-background: #121212;
        --arzaq-bg-primary: #1e293b;
        --arzaq-bg-secondary: #344358;
        --arzaq-bg-tertiary: #3a3a3a;
        --arzaq-bg-invert: #ffffff;
        --arzaq-bg-invert-secondary: #e2e2e2;
        --arzaq-text-primary: #f9fafb;
        --arzaq-text-secondary: #a3a3a3;
        --arzaq-text-tertiary: #313131;
        --arzaq-text-invert: #111827;
        --arzaq-text-invert-secondary: #111827;
        --arzaq-border-primary: #374151;
        --arzaq-border-invert: #d1d5db;
        --arzaq-shadow-color: rgba(0, 0, 0, 0.5);
        --arzaq-shadow-weighted: 0 4px 6px -1px var(--arzaq-shadow-color),
            0 2px 4px -1px var(--arzaq-shadow-color);
    }
</style>