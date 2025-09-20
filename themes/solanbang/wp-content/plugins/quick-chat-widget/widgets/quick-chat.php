<?php
$plugin_base_url = plugin_dir_url(__DIR__);
class Quick_Chat_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'quick-chat';
    }

    public function get_title() {
        return __('Quick Chat', 'quick-chat-widget');
    }

    public function get_icon() {
        return 'eicon-chat';
    }

    public function get_categories() {
        return ['quick-chat'];
    }

    protected function register_controls() {
        // Content section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Liên kết dịch vụ', 'quick-chat-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Control Messenger
        $this->add_control(
            'messenger_link',
            [
                'label' => __('https://www.messenger.com/', 'quick-chat-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('https://m.me/yourpage', 'quick-chat-widget'),
            ]
        );
        $this->add_control(
            'messenger_icon',
            [
                'label' => __('Hình ảnh Messenger', 'quick-chat-widget'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => plugin_dir_url(__DIR__) . 'images/fbmessenger.png',
                ],
                'description' => __('Tải lên hoặc nhập đường dẫn tới hình ảnh Messenger. Nếu để trống, hình ảnh mặc định sẽ được sử dụng.', 'quick-chat-widget'),
            ]
        );

        // Control Phone
        $this->add_control(
            'phone_link',
            [
                'label' => __('Số điện thoại', 'quick-chat-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('tel:+1234567890', 'quick-chat-widget'),
            ]
        );
        $this->add_control(
            'phone_icon',
            [
                'label' => __('Hình ảnh Phone', 'quick-chat-widget'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => plugin_dir_url(__DIR__) .'/images/calling.png',
                ],
                'description' => __('Tải lên hoặc nhập đường dẫn tới hình ảnh Phone. Nếu để trống, hình ảnh mặc định sẽ được sử dụng.', 'quick-chat-widget'),
            ]
        );

        // Control Zalo
        $this->add_control(
            'zalo_link',
            [
                'label' => __('https://chat.zalo.me/', 'quick-chat-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('https://zalo.me/1234567890', 'quick-chat-widget'),
            ]
        );
        $this->add_control(
            'zalo_icon',
            [
                'label' => __('Hình ảnh Zalo', 'quick-chat-widget'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                   'url' => plugin_dir_url(__DIR__) .'/images/zalo.png',
                ],
                'description' => __('Tải lên hoặc nhập đường dẫn tới hình ảnh Zalo. Nếu để trống, hình ảnh mặc định sẽ được sử dụng.', 'quick-chat-widget'),
            ]
        );

        // Control WhatsApp
        $this->add_control(
            'whatsapp_link',
            [
                'label' => __('https://web.whatsapp.com/', 'quick-chat-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('https://wa.me/1234567890', 'quick-chat-widget'),
            ]
        );
        $this->add_control(
            'whatsapp_icon',
            [
                'label' => __('Hình ảnh WhatsApp', 'quick-chat-widget'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                   'url' => plugin_dir_url(__DIR__) .'/images/whatsapp.png', // Sửa lỗi chính tả từ whatshap.svg thành whatsapp.svg
                ],
                'description' => __('Tải lên hoặc nhập đường dẫn tới hình ảnh WhatsApp. Nếu để trống, hình ảnh mặc định sẽ được sử dụng.', 'quick-chat-widget'),
            ]
        );

        $this->end_controls_section();

        // Style section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'quick-chat-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Control button color
        $this->add_control(
            'button_color',
            [
                'label' => __('Màu nút', 'quick-chat-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#5865F2',
                'selectors' => [
                    '{{WRAPPER}} .quick-chat-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Control icon size
        $this->add_control(
            'icon_size',
            [
                'label' => __('Kích thước biểu tượng', 'quick-chat-widget'),
                

            ]
        );

        // Control arrow button color
        $this->add_control(
            'arrow_color',
            [
                'label' => __('Màu nút mũi tên', 'quick-chat-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#007bff',
                'selectors' => [
                    '{{WRAPPER}} .quick-chat-arrow' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    // Render widget on frontend
    protected function render() {
        $settings = $this->get_settings_for_display();
        $messenger = esc_url($settings['messenger_link']);
        $phone = esc_url($settings['phone_link']);
        $zalo = esc_url($settings['zalo_link']);
        $whatsapp = esc_url($settings['whatsapp_link']);
        $messenger_icon = $settings['messenger_icon']['url'];
        $phone_icon = $settings['phone_icon']['url'];
        $zalo_icon = $settings['zalo_icon']['url'];
        $whatsapp_icon = $settings['whatsapp_icon']['url'];
        ?>
        <div class="quick-chat-widget">
            <div class="quick-chat-buttons">
                <?php if ($messenger && $messenger_icon) : ?>
                    <a href="<?php echo $messenger; ?>" class="quick-chat-button messenger" target="_blank">
                        <img src="<?php echo esc_url($messenger_icon); ?>" alt="Messenger">
                        <span>Messenger</span>
                    </a>
                <?php endif; ?>
                <?php if ($phone && $phone_icon) : ?>
                    <a href="<?php echo $phone; ?>" class="quick-chat-button phone" target="_blank">
                        <img src="<?php echo esc_url($phone_icon); ?>" alt="Phone">
                        <span>Gọi điện</span>
                    </a>
                <?php endif; ?>
                <?php if ($zalo && $zalo_icon) : ?>
                    <a href="<?php echo $zalo; ?>" class="quick-chat-button zalo" target="_blank">
                        <img src="<?php echo esc_url($zalo_icon); ?>" alt="Zalo">
                        <span>Zalo</span>
                    </a>
                <?php endif; ?>
                <?php if ($whatsapp && $whatsapp_icon) : ?>
                    <a href="<?php echo $whatsapp; ?>" class="quick-chat-button whatsapp" target="_blank">
                        <img src="<?php echo esc_url($whatsapp_icon); ?>" alt="WhatsApp">
                        <span>WhatsApp</span>
                    </a>
                <?php endif; ?>
            </div>
            <button class="quick-chat-arrow" style="display: none;">
                <i class="fas fa-chevron-up"></i>
            </button>
        </div>

 

        
        <?php
    }

    // Render in Elementor editor
 protected function content_template() {
        ?>
        <div class="quick-chat-widget">
            <div class="quick-chat-buttons">
                <# if ( settings.messenger_link && settings.messenger_icon.url ) { #>
                    <a href="{{ settings.messenger_link }}" class="quick-chat-button messenger" target="_blank">
                        <img src="{{ settings.messenger_icon.url }}" alt="Messenger">
                        <span>Messenger</span>
                    </a>
                <# } #>
                <# if ( settings.phone_link && settings.phone_icon.url ) { #>
                    <a href="{{ settings.phone_link }}" class="quick-chat-button phone" target="_blank">
                        <img src="{{ settings.phone_icon.url }}" alt="Phone">
                        <span>Gọi điện</span>
                    </a>
                <# } #>
                <# if ( settings.zalo_link && settings.zalo_icon.url ) { #>
                    <a href="{{ settings.zalo_link }}" class="quick-chat-button zalo" target="_blank">
                        <img src="{{ settings.zalo_icon.url }}" alt="Zalo">
                        <span>Zalo</span>
                    </a>
                <# } #>
                <# if ( settings.whatsapp_link && settings.whatsapp_icon.url ) { #>
                    <a href="{{ settings.whatsapp_link }}" class="quick-chat-button whatsapp" target="_blank">
                        <img src="{{ settings.whatsapp_icon.url }}" alt="WhatsApp">
                        <span>WhatsApp</span>
                    </a>
                <# } #>
            </div>
            <button class="quick-chat-arrow" style="display: none;">
                <i class="fas fa-chevron-up"></i>
            </button>
        </div>
        <?php
    }
}