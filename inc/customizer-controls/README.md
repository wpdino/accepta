# Accepta Advanced Customizer Controls

This directory contains custom WordPress Customizer controls for the **Accepta** theme by **WPDINO**. These controls provide Elementor-style spacing controls with responsive breakpoints and unit selection.

## Features

### 🎛️ Advanced Spacing Control
- **Visual Interface**: Elementor-style visual spacing control with top, right, bottom, left inputs
- **Responsive Design**: Desktop, tablet, and mobile breakpoints
- **Unit Selection**: Support for px, em, rem, %, vh, vw
- **Linked Values**: Option to link all sides for uniform spacing
- **Live Preview**: Real-time updates in customizer preview

### 📱 Responsive Breakpoints
- **Desktop**: > 782px width (WordPress core "medium" breakpoint)
- **Tablet**: 600px - 782px width (WordPress core "small" breakpoint)  
- **Mobile**: < 600px width (WordPress core "mobile" breakpoint)

### 🔧 Usage

```php
// Add spacing control to customizer
$wp_customize->add_setting(
    'my_spacing_setting',
    array(
        'default'           => json_encode(array(
            'desktop' => array( 'top' => '20', 'right' => '15', 'bottom' => '20', 'left' => '15', 'unit' => 'px', 'linked' => false ),
            'tablet'  => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '', 'unit' => 'px', 'linked' => false ),
            'mobile'  => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '', 'unit' => 'px', 'linked' => false ),
        )),
        'sanitize_callback' => 'accepta_sanitize_spacing',
        'transport'         => 'postMessage',
    )
);

$wp_customize->add_control(
    new Accepta_Spacing_Control(
        $wp_customize,
        'my_spacing_setting',
        array(
            'label'       => __( 'My Spacing Control', 'textdomain' ),
            'description' => __( 'Control spacing with responsive options.', 'textdomain' ),
            'section'     => 'my_section',
            'priority'    => 10,
            'responsive'  => true,
            'units'       => array( 'px', 'em', 'rem', '%' ),
            'default_unit' => 'px',
        )
    )
);
```

### 🎨 CSS Generation

The control generates responsive CSS automatically:

```php
function generate_spacing_css( $setting_name, $selector ) {
    $spacing_json = get_theme_mod( $setting_name, '' );
    $spacing = json_decode( $spacing_json, true );
    
    if ( ! is_array( $spacing ) ) {
        return '';
    }

    $css = '';
    $breakpoints = array(
        'desktop' => '@media (min-width: 783px)',
        'tablet'  => '@media (min-width: 600px) and (max-width: 782px)',
        'mobile'  => '@media (max-width: 599px)'
    );

    foreach ( $breakpoints as $device => $media_query ) {
        if ( isset( $spacing[ $device ] ) && is_array( $spacing[ $device ] ) ) {
            $device_css = '';
            $s = $spacing[ $device ];
            $unit = isset( $s['unit'] ) ? $s['unit'] : 'px';
            
            foreach ( array( 'top', 'right', 'bottom', 'left' ) as $side ) {
                if ( ! empty( $s[ $side ] ) ) {
                    $device_css .= "padding-{$side}: {$s[$side]}{$unit};";
                }
            }
            
            if ( ! empty( $device_css ) ) {
                if ( ! empty( $media_query ) ) {
                    $css .= $media_query . ' {';
                }
                $css .= $selector . ' {' . $device_css . '}';
                if ( ! empty( $media_query ) ) {
                    $css .= '}';
                }
            }
        }
    }
    
    return $css;
}
```

## Files Structure

```
inc/customizer-controls/
├── class-accepta-spacing-control.php    # Main control class
├── css/
│   └── spacing-control.css              # Control styling
├── js/
│   └── spacing-control.js               # Control JavaScript
└── README.md                            # This documentation
```

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## Dependencies

- WordPress 4.7+
- jQuery (included with WordPress)
- WordPress Customizer API
