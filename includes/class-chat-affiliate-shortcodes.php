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
            $output = '<div class="row">';  // Bootstrap grid sistemi için row ekleniyor
    
            while ($products->have_posts()) {
                $products->the_post();
                $id = get_the_ID();
                $title = get_the_title();
                $price = get_post_meta($id, '_price', true);
                $description = get_post_meta($id, '_description', true);
                $target_url = get_post_meta($id, '_target_url', true);
                $thumbnail_url = get_the_post_thumbnail_url($id, 'thumbnail');
    
                $output .= '<div class="col-md-4">';  // Her ürün için bir kolon oluşturuluyor
                $output .= '<div class="card">';  // Bootstrap card componenti
                if ($thumbnail_url) {
                    $output .= '<img src="' . $thumbnail_url . '" class="card-img-top" alt="' . $title . '">';  // Ürün resmi
                }
                $output .= '<div class="card-body">';
                $output .= '<h5 class="card-title">' . $title . '</h5>';  // Ürün başlığı
    
                if ($atts['fiyat'] === 'true' && $price) {
                    $output .= '<p class="card-text">Fiyat: $' . $price . '</p>';  // Fiyat bilgisi
                }
                if ($atts['aciklama'] === 'true' && $description) {
                    $output .= '<p class="card-text">' . $description . '</p>';  // Açıklama
                }
    
                $output .= '<a href="' . $target_url . '" class="btn btn-primary" target="_blank">İncele</a>';  // İncele butonu
                $output .= '</div>';
                $output .= '</div>';
                $output .= '</div>';
            }
    
            $output .= '</div>';
    
            wp_reset_postdata();
            return $output;
        } else {
            return '<p>Ürün bulunamadı.</p>';
        }
    }
    
}

new Chat_Affiliate_Shortcodes();
