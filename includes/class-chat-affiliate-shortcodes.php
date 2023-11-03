<?php
class Chat_Affiliate_Shortcodes {

    public function __construct() {
        add_shortcode('urunler', array($this, 'render_products_shortcode'));
    }

    public function render_products_shortcode($atts) {
        $atts = shortcode_atts(
            array(
                'fiyat' => 'true',
                'aciklama' => 'true',
            ),
            $atts,
            'urunler'
        );
    
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
        );
        $products = new WP_Query($args);
    
        if ($products->have_posts()) {
            $output = '
            <h2 class="product-main-title">Related Products</h2>
            <div class="product-container">';  // Bootstrap grid sistemi için row ekleniyor
    
            while ($products->have_posts()) {
                $products->the_post();
                $id = get_the_ID();
                $title = get_the_title();
                $price = get_post_meta($id, '_price', true);
                $description = get_post_meta($id, '_description', true);
                $target_url = get_post_meta($id, '_target_url', true);
                $thumbnail_url = get_the_post_thumbnail_url($id, 'thumbnail');
    
                $output .= '<div class="product-item">';  // Her ürün için bir kolon oluşturuluyor
               
                if ($thumbnail_url) {
                    $output .= '<img src="' . $thumbnail_url . '" class="card-img-top" alt="' . $title . '">';  // Ürün resmi
                }
                $output .= '<div class="product-item-details">';
                $output .= '<h2 class="product-item-title">' . $title . '</h2>';  // Ürün başlığı
    
                if ($atts['fiyat'] === 'true' && $price) {
                    $output .= '<p class="product-item-price">Price: $' . $price . '</p>';  // Fiyat bilgisi
                }
                if ($atts['aciklama'] === 'true' && $description) {
                    $output .= '<p class="product-item-description">' . $description . '</p>';  // Açıklama
                }
    
                $output .= '<a href="' . $target_url . '" class="product-item-button" target="_blank" rel="nofollow">Visit to Website</a>';  // İncele butonu
                $output .= '</div>';
                $output .= '</div>';
            }
    
            $output .= '</div>';
    
            wp_reset_postdata();
            return $output;
        }
    }
    
}

new Chat_Affiliate_Shortcodes();
