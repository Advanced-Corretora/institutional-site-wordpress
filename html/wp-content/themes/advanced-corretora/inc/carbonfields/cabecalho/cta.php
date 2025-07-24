<?php
/**
 * Cabeçalho - Campos do CTA
 */

use Carbon_Fields\Field;
/** @var \Carbon_Fields\Field\Select_Field */
return [
    // Cabeçalho - CTA
    Field::make( 'separator', 'crb_header_cta_sep', 'CTA do Cabeçalho' ),
    
    Field::make( 'text', 'crb_header_cta_text', 'Texto do CTA' )
        ->set_help_text( 'Texto exibido no botão CTA do cabeçalho' ),
        
    Field::make( 'text', 'crb_header_cta_url', 'URL do Link' )
        ->set_help_text( 'URL para onde o CTA deve redirecionar' )
        ->set_attribute( 'placeholder', 'https://' ),
     
    Field::make( 'select', 'crb_header_cta_target', 'Abrir link em' )
        ->set_options([// phpcs:ignore
            '_self' => 'Abrir na mesma aba',
            '_blank' => 'Abrir em uma nova aba'    
        ])
        ->set_default_value( '_self' )
        ->set_help_text( 'Use "_self" para abrir na mesma aba ou "_blank" para abrir em uma nova aba' ),
]; 
