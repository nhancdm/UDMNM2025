# Nhan WordPress Theme

A modern, responsive, and feature-rich WordPress theme designed specifically for Nhan. This theme combines clean design with powerful functionality to create an exceptional user experience.

## Features

### 🎨 Design & Layout
- **Modern Design**: Clean, professional layout with gradient accents
- **Fully Responsive**: Optimized for all devices (desktop, tablet, mobile)
- **Customizable Colors**: Built-in color palette with primary brand colors
- **Typography**: Beautiful typography with Google Fonts integration
- **Dark Mode Ready**: Prepared for dark mode implementation

### 🚀 Performance
- **Optimized Code**: Clean, semantic HTML5 and efficient CSS
- **Fast Loading**: Optimized assets and lazy loading support
- **SEO Friendly**: Structured data and semantic markup
- **Accessibility**: WCAG 2.1 compliant with keyboard navigation support

### 📱 Responsive Features
- **Mobile-First Design**: Optimized for mobile devices
- **Touch-Friendly**: Large tap targets and smooth interactions
- **Flexible Grid**: CSS Grid and Flexbox for modern layouts

### 🛠 Functionality
- **Custom Post Types Support**: Ready for custom content types
- **Widget Areas**: Multiple widget areas (sidebar, footer)
- **Navigation Menus**: Primary and footer navigation support
- **Social Media Integration**: Built-in social media links
- **Search Functionality**: Enhanced search with custom styling
- **Comment System**: Styled comment forms and threading
- **Breadcrumb Navigation**: Automatic breadcrumb generation

### 🎛 Customization
- **WordPress Customizer**: Full customizer support
- **Custom Logo**: Upload and display custom logo
- **Custom Header**: Flexible header customization
- **Custom Background**: Background color and image options
- **Footer Text**: Customizable footer copyright text
- **Social Links**: Add social media profile links

## Installation

1. **Download the theme** files to your local machine
2. **Upload to WordPress**:
   - Via Admin Panel: Go to `Appearance > Themes > Add New > Upload Theme`
   - Via FTP: Upload the `nhan` folder to `/wp-content/themes/`
3. **Activate the theme** from `Appearance > Themes`
4. **Customize** your site via `Appearance > Customize`

## File Structure

```
nhan/
├── style.css              # Main stylesheet with theme header
├── index.php              # Main template file
├── functions.php          # Theme functions and features
├── header.php             # Header template
├── footer.php             # Footer template
├── sidebar.php            # Sidebar template
├── single.php             # Single post template
├── page.php               # Static page template
├── 404.php                # 404 error page template
├── searchform.php         # Search form template
├── comments.php           # Comments template
├── js/
│   └── theme.js           # Theme JavaScript functionality
└── README.md              # This documentation file
```

## Template Hierarchy

The theme follows WordPress template hierarchy:

- **Homepage**: `index.php`
- **Single Posts**: `single.php`
- **Pages**: `page.php`
- **404 Errors**: `404.php`
- **Search Results**: `index.php`
- **Archives**: `index.php`

## Customization Options

### Theme Customizer
Access via `Appearance > Customize`:

1. **Site Identity**
   - Site Title & Tagline
   - Custom Logo
   - Site Icon (Favicon)

2. **Colors**
   - Custom color schemes
   - Header and background colors

3. **Nhan Theme Options**
   - Footer text customization
   - Social media links (Facebook, Twitter, Instagram, LinkedIn, YouTube)

4. **Menus**
   - Primary Navigation
   - Footer Navigation

5. **Widgets**
   - Primary Sidebar
   - Footer Widget Areas (3 columns)

### Widget Areas

The theme includes several widget areas:

- **Primary Sidebar**: Main sidebar for blog posts
- **Footer Widget Area 1**: First footer column
- **Footer Widget Area 2**: Second footer column  
- **Footer Widget Area 3**: Third footer column

### Navigation Menus

Two menu locations are available:

- **Primary Menu**: Main navigation in header
- **Footer Menu**: Links in footer area

## CSS Classes & Styling

### Utility Classes
```css
.text-center    /* Center align text */
.text-right     /* Right align text */
.mb-1          /* Margin bottom 1rem */
.mb-2          /* Margin bottom 2rem */
.mt-1          /* Margin top 1rem */
.mt-2          /* Margin top 2rem */
```

### WordPress Specific Classes
```css
.alignleft     /* Float left with margins */
.alignright    /* Float right with margins */
.aligncenter   /* Center align with margins */
.wp-caption    /* Image caption styling */
.sticky        /* Sticky post highlighting */
```

### Color Palette
```css
Primary: #667eea
Secondary: #764ba2
Dark: #2c3e50
Light: #f8f9fa
```

## JavaScript Features

### Interactive Elements
- **Mobile Menu**: Responsive hamburger menu
- **Search Toggle**: Expandable search form
- **Smooth Scrolling**: Anchor link animations
- **Back to Top**: Scroll-to-top button
- **Comment Enhancements**: Form validation and character counter

### Performance Features
- **Lazy Loading**: Image lazy loading fallback
- **Debounced Events**: Optimized scroll and resize handlers
- **External Links**: Automatic external link detection

## Browser Support

- **Modern Browsers**: Chrome, Firefox, Safari, Edge (latest versions)
- **Mobile Browsers**: iOS Safari, Chrome Mobile, Samsung Internet
- **Fallbacks**: Graceful degradation for older browsers

## Performance Optimization

### Built-in Optimizations
- **Minified Assets**: Compressed CSS and JavaScript
- **Optimized Images**: Responsive image support
- **Efficient Queries**: Optimized database queries
- **Caching Ready**: Compatible with caching plugins

### Recommended Plugins
- **Yoast SEO**: Enhanced SEO features
- **W3 Total Cache**: Performance caching
- **Smush**: Image optimization
- **Contact Form 7**: Contact forms

## Security Features

- **Sanitized Inputs**: All user inputs are sanitized
- **Escaped Outputs**: All outputs are properly escaped
- **Security Headers**: Basic security headers included
- **WordPress Standards**: Follows WordPress coding standards

## Accessibility

### WCAG 2.1 Compliance
- **Keyboard Navigation**: Full keyboard accessibility
- **Screen Reader Support**: Proper ARIA labels and semantic markup
- **Color Contrast**: Sufficient color contrast ratios
- **Focus Management**: Visible focus indicators

### Accessibility Features
- **Skip Links**: Skip to main content
- **Alt Text**: Image alt text support
- **Form Labels**: Proper form labeling
- **Heading Structure**: Logical heading hierarchy

## Development

### Requirements
- **WordPress**: 5.0 or higher
- **PHP**: 7.4 or higher
- **MySQL**: 5.6 or higher

### Development Tools
- **CSS**: Modern CSS with Grid and Flexbox
- **JavaScript**: ES6+ with jQuery fallback
- **Build Tools**: Manual optimization (can be extended with build tools)

### Coding Standards
- **WordPress Coding Standards**: Follows WordPress PHP and CSS standards
- **PSR Standards**: PHP code follows PSR-12 standards
- **Accessibility**: WCAG 2.1 AA compliance

## Changelog

### Version 1.0.0
- Initial release
- Complete theme structure
- Responsive design
- Customizer integration
- JavaScript functionality
- Accessibility features
- SEO optimization

## Support & Documentation

### Getting Help
1. **WordPress Codex**: [https://codex.wordpress.org/](https://codex.wordpress.org/)
2. **Theme Documentation**: This README file
3. **WordPress Forums**: Community support available

### Customization Services
For custom modifications or additional features, consider hiring a WordPress developer familiar with the theme structure.

## License

This theme is licensed under the GPL v2 or later.

```
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
```

## Credits

### Third-Party Resources
- **Font Awesome**: Icons (https://fontawesome.com/)
- **Google Fonts**: Typography (https://fonts.google.com/)
- **jQuery**: JavaScript library (https://jquery.com/)

### Inspiration
- Modern web design trends
- WordPress theme best practices
- User experience principles

---

**Theme Name**: Nhan  
**Version**: 1.0.0  
**Author**: Nhan  
**Description**: A modern, responsive WordPress theme  
**Tags**: responsive, modern, clean, blog, business  

For questions or support, please refer to the WordPress community forums or consult with a WordPress developer.
