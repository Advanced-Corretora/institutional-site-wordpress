<?php
// Carrega o autoloader do Composer (garanta que está no início)
require_once get_template_directory() . '/vendor/autoload.php';

use Carbon_Fields\Carbon_Fields;
use Carbon_Fields\Container;
use Carbon_Fields\Field;

// Inicializa o Carbon Fields após o tema estar pronto
add_action( 'after_setup_theme', function() {
    Carbon_Fields::boot();
});

// Registra os campos de opções do tema
add_action( 'carbon_fields_register_fields', function() {
    Container::make( 'theme_options', __( 'Opções do Tema' ) )
        ->add_fields( [
            Field::make( 'text', 'crb_text', 'Campo de Texto' ),
        ] );
});
