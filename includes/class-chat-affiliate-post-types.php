<?php

// Güvenlik için doğrudan dosya erişimini engelle
if ( ! defined( 'ABSPATH' ) ) {
    die( 'Erişim engellendi!' );
}

/**
 * Chat_Affiliate_Post_Types Class - Özel post türlerini tanımlar.
 */
class Chat_Affiliate_Post_Types {

    /**
     * Sınıfın başlatıcı fonksiyonu.
     */
    public function __construct() {
        add_action( 'init', array( $this, 'register_product_post_type' ) );
        //add_action( 'init', array( $this, 'register_category_post_type' ) );
        add_action( 'init', array( $this, 'register_product_category_taxonomy' ) );
    }

   /**
     * Ürün yazı türünü tanımlar.
     */
    public function register_product_post_type() {
        $labels = array(
            // ...
        );

        $args = array(
            'label'               => __( 'Ürün', 'chat-affiliate' ),
            'description'         => __( 'Affiliate ürünleri.', 'chat-affiliate' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
            'taxonomies'          => array(),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-cart',
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => false,
            'publicly_queryable'  => false,  // Bu satırı ekledik
            'rewrite'             => false,  // Bu satırı ekledik
            'capability_type'     => 'post',
        );

        register_post_type( 'product', $args );
    }


    /**
     * Kategoriler için özel post türünü tanımlar.
     */
    public function register_category_post_type() {
        $labels = array(
            'name'               => _x( 'Kategoriler', 'post type general name', 'chat-affiliate' ),
            'singular_name'      => _x( 'Kategori', 'post type singular name', 'chat-affiliate' ),
            'menu_name'          => _x( 'Kategoriler', 'admin menu', 'chat-affiliate' ),
            'name_admin_bar'     => _x( 'Kategori', 'add new on admin bar', 'chat-affiliate' ),
            'add_new'            => _x( 'Kategori Ekle', 'category', 'chat-affiliate' ),
            'add_new_item'       => __( 'Yeni Kategori Ekle', 'chat-affiliate' ),
            'new_item'           => __( 'Yeni Kategori', 'chat-affiliate' ),
            'edit_item'          => __( 'Kategoriyi Düzenle', 'chat-affiliate' ),
            'view_item'          => __( 'Kategoriyi Görüntüle', 'chat-affiliate' ),
            'all_items'          => __( 'Tüm Kategoriler', 'chat-affiliate' ),
            'search_items'       => __( 'Kategori Ara', 'chat-affiliate' ),
            'parent_item_colon'  => __( 'Üst Kategori:', 'chat-affiliate' ),
            'not_found'          => __( 'Kategori bulunamadı.', 'chat-affiliate' ),
            'not_found_in_trash' => __( 'Çöp kutusunda kategori bulunamadı.', 'chat-affiliate' )
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Kategorilerinizi tanımlamanıza olanak sağlar.', 'chat-affiliate' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'kategori' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
        );

        register_post_type( 'category', $args );
    }

    /**
     * Ürün Kategorileri taksonomisini tanımlar.
     */
    public function register_product_category_taxonomy() {
        $labels = array(
            'name'              => _x( 'Ürün Kategorileri', 'taxonomy general name', 'chat-affiliate' ),
            'singular_name'     => _x( 'Ürün Kategorisi', 'taxonomy singular name', 'chat-affiliate' ),
            'search_items'      => __( 'Kategorileri Ara', 'chat-affiliate' ),
            'all_items'         => __( 'Tüm Kategoriler', 'chat-affiliate' ),
            'parent_item'       => __( 'Üst Kategori', 'chat-affiliate' ),
            'parent_item_colon' => __( 'Üst Kategori:', 'chat-affiliate' ),
            'edit_item'         => __( 'Kategoriyi Düzenle', 'chat-affiliate' ),
            'update_item'       => __( 'Kategoriyi Güncelle', 'chat-affiliate' ),
            'add_new_item'      => __( 'Yeni Kategori Ekle', 'chat-affiliate' ),
            'new_item_name'     => __( 'Yeni Kategori Adı', 'chat-affiliate' ),
            'menu_name'         => __( 'Kategoriler', 'chat-affiliate' ),
        );
    
        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'product-category' ),
            'publicly_queryable' => false,
        );
    
        register_taxonomy( 'product_category', array( 'product' ), $args );
    }
    

}

// Sınıfı başlat
new Chat_Affiliate_Post_Types();
