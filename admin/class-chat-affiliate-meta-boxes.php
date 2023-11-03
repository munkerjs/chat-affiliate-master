<?php

// Güvenlik için doğrudan dosya erişimini engelle
if ( ! defined( 'ABSPATH' ) ) {
    die( 'Erişim engellendi!' );
}

/**
 * Chat_Affiliate_Meta_Boxes Class - Ürünler için özel alanları (metabox) tanımlar.
 */
class Chat_Affiliate_Meta_Boxes {

    /**
     * Sınıfın başlatıcı fonksiyonu.
     */
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_product_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_product_meta_boxes' ) );

        // ÜRÜNLER TABLOSU İÇİN ÖZEL ALANLAR
        add_filter( 'manage_product_posts_columns', array( $this, 'modify_product_columns' ) );
        add_action( 'manage_product_posts_custom_column', array( $this, 'render_product_columns' ), 10, 2 );
        add_action( 'restrict_manage_posts', array( $this, 'filter_products_by_category' ) );
        add_filter( 'parse_query', array( $this, 'perform_filtering_by_category' ) );
        add_action( 'admin_menu', array( $this, 'remove_unnecessary_meta_boxes' ) );

        add_action( 'add_meta_boxes', array( $this, 'add_post_products_metabox' ) );
        add_action( 'save_post', array( $this, 'save_post_products_metabox' ) );

        add_action('wp_ajax_record_product_click', array($this, 'record_product_click'));
        add_action('wp_ajax_nopriv_record_product_click', array($this, 'record_product_click'));

        add_filter('manage_edit-product_sortable_columns', array($this, 'sortable_columns'));
        add_action('pre_get_posts', array($this, 'custom_orderby'));

    }

    /**
     * Ürünler için özel alanları tanımlar.
     */
    public function add_product_meta_boxes() {
        add_meta_box( 'product_info', 'Ürün Bilgileri', array( $this, 'render_product_info_metabox' ), 'product', 'normal', 'high' );
    }

    /**
     * Ürün Bilgileri metabox'ını oluşturur.
     */
    public function render_product_info_metabox( $post ) {
        // Nonce field ekleyerek güvenlik önlemi alıyoruz
        wp_nonce_field( 'product_info_save', 'product_info_nonce' );

        // Özel alanların değerlerini alıyoruz
        $target_url = get_post_meta( $post->ID, '_target_url', true );
        $price = get_post_meta( $post->ID, '_price', true );
        $category = get_post_meta( $post->ID, '_category', true );

        // Metabox içeriğini oluşturuyoruz
        echo '<p><label for="target_url">Hedef URL: </label>';
        echo '<input type="text" id="target_url" name="target_url" value="' . esc_attr( $target_url ) . '" size="25" style="width:100%;" /></p>';
        echo '<p><label for="price">Fiyat ($): </label>';
        echo '<input type="text" id="price" name="price" value="' . esc_attr( $price ) . '" size="25" style="width:100%;"/></p>';
    }


    /**
     * Ürün Bilgileri metabox'ını kaydeder.
     */
    public function save_product_meta_boxes( $post_id ) {
        // Nonce kontrolü yaparak güvenlik önlemi alıyoruz
        if ( ! isset( $_POST['product_info_nonce'] ) || ! wp_verify_nonce( $_POST['product_info_nonce'], 'product_info_save' ) ) {
            return;
        }

        // Otomatik kaydetme işlemlerinde bir şey yapmıyoruz
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Kullanıcının izni olup olmadığını kontrol ediyoruz
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Özel alanları kaydediyoruz
        if ( isset( $_POST['target_url'] ) ) {
            update_post_meta( $post_id, '_target_url', sanitize_text_field( $_POST['target_url'] ) );
        }
        if ( isset( $_POST['price'] ) ) {
            update_post_meta( $post_id, '_price', sanitize_text_field( $_POST['price'] ) );
        }
        if ( isset( $_POST['product_category'] ) ) {
            wp_set_post_terms( $post_id, (int)$_POST['product_category'], 'product_category' );
        }

        if (!get_post_meta($post_id, '_click_count', true)) {
            update_post_meta($post_id, '_click_count', 0);
        }
    }

    /**
     * Gereksiz metabox'ları kaldırır.
     */
    public function remove_unnecessary_meta_boxes() {
        remove_meta_box( 'commentstatusdiv', 'product', 'normal' ); // Tartışma
        remove_meta_box( 'postexcerpt', 'product', 'normal' );      // Özet Kısmı
    }

    /**
     * Ürünler listesindeki kolonları günceller.
     */
    public function modify_product_columns( $columns ) {
        // Yazar, Tarih ve Yorumlar kolonlarını kaldır
        unset($columns['author']);
        unset($columns['date']);
        unset($columns['comments']);

        // Yeni kolonlar ekleyin
        $columns['featured_image'] = 'Ürün Fotoğrafı';
        $columns['title'] = 'Başlık';
        $columns['price'] = 'Fiyat';
        $columns['target_url'] = 'Hedef URL';
        $columns['status'] = 'Yayınlanma Durumu';
        $columns['created_at'] = 'Yayınlanma Tarihi';
        $columns['click_count'] = 'Tıklanma Sayısı';


        return $columns;
    }

    public function sortable_columns($columns) {
        $columns['click_count'] = 'click_count';
        $columns['price'] = 'price';
        $columns['date'] = 'date';
        $columns['category'] = 'category';
        return $columns;
    }

    public function custom_orderby($query) {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }

        $orderby = $query->get('orderby');

        if ($orderby == 'click_count') {
            $query->set('meta_key', '_click_count');
            $query->set('orderby', 'meta_value_num');
        } elseif ($orderby == 'price') {
            $query->set('meta_key', '_price');
            $query->set('orderby', 'meta_value_num');
        } elseif ($orderby == 'category') {
            $query->set('meta_key', '_category');
            $query->set('orderby', 'meta_value');
        }
    }


    /**
     * Özel kolonların içeriğini oluşturur.
     */
    public function render_product_columns( $column, $post_id ) {
        switch ( $column ) {
            case 'featured_image':
                $thumbnail_id = get_post_thumbnail_id( $post_id );
                $thumbnail_url = wp_get_attachment_image_src( $thumbnail_id, 'thumbnail_size' );
                if (is_array($thumbnail_url)) {
                    echo '<img src="' . esc_url($thumbnail_url[0]) . '" width="50" height="50" alt="Ürün Fotoğrafı">';
                }
                break;
            case 'price':
                echo get_post_meta( $post_id, '_price', true );
                break;
            case 'target_url':
                $target_url = get_post_meta( $post_id, '_target_url', true );
                echo '<a href="' . esc_url($target_url) . '" target="_blank">İncele</a>';
                break; 
            case 'click_count':
                $click_count = get_post_meta($post_id, '_click_count', true);
                echo $click_count ? $click_count : '0';
                break;
            case 'status':
                $status = get_post_meta($post_id, '_status', true);            
                // Yayınlanma durumunu kontrol etme
                if ($status === true || $status === 'true' || $status === 1 || $status === '1') {
                    echo '<span class="dashicons dashicons-yes"></span>';  // Check ikonu
                } else {
                    echo '<span class="dashicons dashicons-no"></span>';   // Close ikonu
                }
                break;
            case 'created_at':
                echo get_the_date( 'd.m.Y H:i:s', $post_id );
                break;
        }
    }


    /**
     * Ürünler listesine kategoriye göre filtreleme ekler.
     */
    public function filter_products_by_category() {
        global $typenow;
        if ($typenow == 'product') {
            $selected = isset($_GET['product_category']) ? $_GET['product_category'] : '';
            $categories = get_terms('product_category');
            if (!empty($categories)) {
                echo '<select name="product_category">';
                echo '<option value="">Kategori Seçiniz</option>';
                foreach ($categories as $category) {
                    echo '<option value="' . $category->slug . '" ' . selected($selected, $category->slug) . '>' . $category->name . '</option>';
                }
                echo '</select>';
            }
        }
    }

    /**
     * Kategoriye göre filtreleme işlemini gerçekleştirir.
     */
    public function perform_filtering_by_category( $query ) {
        global $pagenow;
        $type = 'product';
        if (isset($_GET['post_type'])) {
            $type = $_GET['post_type'];
        }
        if ('product' == $type && is_admin() && $pagenow == 'edit.php' && isset($_GET['product_category']) && $_GET['product_category'] != '') {
            $query->query_vars['tax_query'] = array(
                array(
                    'taxonomy' => 'product_category',
                    'field' => 'slug',
                    'terms' => $_GET['product_category']
                )
            );
        }
    }
    
    /**
     * Blog yazıları için ürün seçme metabox'ını ekler.
     */
    public function add_post_products_metabox() {
        add_meta_box(
            'post_products',
            'İlgili Ürünler',
            array( $this, 'render_post_products_metabox' ),
            'post',
            'normal',
            'high'
        );
    }
    
    /**
     * Blog yazıları için ürün seçme metabox'ının içeriğini oluşturur.
     */
    public function render_post_products_metabox( $post ) {
        $selected_products = get_post_meta( $post->ID, '_related_products', true );
        $products = get_posts(array('post_type' => 'product', 'numberposts' => -1));
        echo '<select id="related_products" name="related_products[]" multiple="multiple" style="width:100%;">';
        foreach ($products as $product) {
            $selected = in_array($product->ID, (array)$selected_products) ? 'selected="selected"' : '';
            echo '<option value="' . $product->ID . '" ' . $selected . '>' . $product->post_title . '</option>';
        }
        echo '</select>';
    }
    
    /**
     * Blog yazıları için seçilen ürünleri kaydeder.
     */
    public function save_post_products_metabox( $post_id ) {
        if ( isset( $_POST['related_products'] ) ) {
            update_post_meta( $post_id, '_related_products', $_POST['related_products'] );
        }
    }

    public function record_product_click() {
        $product_id = $_POST['product_id'];
        $user_ip = $_SERVER['REMOTE_ADDR'];
    
        // IP kontrolü
        $ip_list = get_post_meta($product_id, '_click_ip_list', true);
        $ip_list = $ip_list ? json_decode($ip_list, true) : array();
        if (!in_array($user_ip, $ip_list)) {
            // Tıklama sayısını artırma
            $click_count = get_post_meta($product_id, '_click_count', true);
            $click_count = $click_count ? $click_count + 1 : 1;
            update_post_meta($product_id, '_click_count', $click_count);
    
            // IP'yi listeye ekleme
            $ip_list[] = $user_ip;
            update_post_meta($product_id, '_click_ip_list', json_encode($ip_list));
        }
    
        echo json_encode(array('success' => true));
        wp_die();
    }

}

// Sınıfı başlat
new Chat_Affiliate_Meta_Boxes();
