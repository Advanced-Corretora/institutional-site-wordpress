<?php
/**
 * Campos Carbon Fields para o Rodapé
 * 
 * Define todos os campos necessários para configurar o rodapé do site
 */

use Carbon_Fields\Field;

return array(
    // Seção Menu do Rodapé
    Field::make( 'separator', 'footer_menu_separator', __( 'Menu do Rodapé' ) ),
    Field::make( 'html', 'footer_menu_info' )
        ->set_html( '<p><strong>Informação:</strong> O menu do rodapé será exibido automaticamente baseado no menu configurado em <em>Aparência > Menus</em>. Configure um menu com 2 níveis (pai e filhos) e atribua-o à localização "Footer Menu".</p>' ),

    // Seção Redes Sociais
    Field::make( 'separator', 'footer_social_separator', __( 'Redes Sociais' ) ),
    Field::make( 'complex', 'footer_social_networks', __( 'Redes Sociais' ) )
        ->add_fields( array(
            Field::make( 'image', 'social_icon', __( 'Ícone da Rede Social' ) ),
            Field::make( 'text', 'social_link', __( 'Link da Rede Social' ) )
                ->set_attribute( 'type', 'url' ),
            Field::make( 'text', 'social_name', __( 'Nome da Rede Social' ) )
                ->set_help_text( 'Nome para acessibilidade (alt text)' )
        ) ),
    Field::make( 'rich_text', 'footer_social_disclaimer', __( 'Disclaimer (Texto abaixo das redes sociais)' ) )
        ->set_help_text( 'Texto livre com suporte a HTML. Pode incluir links e formatação.' ),

    // Seção Unidades
    Field::make( 'separator', 'footer_units_separator', __( 'Unidades' ) ),
    Field::make( 'complex', 'footer_units', __( 'Unidades' ) )
        ->add_fields( array(
            Field::make( 'rich_text', 'unit_title', __( 'Título da Unidade' ) )
                ->set_help_text( 'Pode incluir HTML se necessário' ),
            Field::make( 'rich_text', 'unit_phone', __( 'Telefone da Unidade' ) )
                ->set_help_text( 'Pode incluir HTML se necessário' )
        ) ),

    // Seção Selos
    Field::make( 'separator', 'footer_seals_separator', __( 'Selos' ) ),
    Field::make( 'complex', 'footer_seals', __( 'Selos' ) )
        ->add_fields( array(
            Field::make( 'text', 'seal_title', __( 'Título do Selo' ) ),
            Field::make( 'image', 'seal_image', __( 'Imagem do Selo' ) ),
            Field::make( 'text', 'seal_link', __( 'Link do Selo (Opcional)' ) )
                ->set_attribute( 'type', 'url' )
        ) ),

    // Política de Privacidade
    Field::make( 'separator', 'footer_privacy_separator', __( 'Política de Privacidade' ) ),
    Field::make( 'text', 'privacy_button1_text', __( 'Texto do Botão 1' ) )
        ->set_default_value( 'Política de Privacidade' ),
    Field::make( 'text', 'privacy_button1_link', __( 'Link do Botão 1' ) )
        ->set_attribute( 'type', 'url' ),
    Field::make( 'text', 'privacy_button2_text', __( 'Texto do Botão 2' ) )
        ->set_default_value( 'Termos de Uso' ),
    Field::make( 'text', 'privacy_button2_link', __( 'Link do Botão 2' ) )
        ->set_attribute( 'type', 'url' ),

    // Seção Logos Advanced Grupo
    Field::make( 'separator', 'footer_logos_separator', __( 'Logos Advanced Grupo' ) ),
    Field::make( 'complex', 'footer_advanced_logos', __( 'Logos Advanced Grupo' ) )
        ->add_fields( array(
            Field::make( 'image', 'logo_image', __( 'Imagem do Logo' ) ),
            Field::make( 'checkbox', 'logo_mobile_enabled', __( 'Habilitar no Mobile' ) )
                ->set_default_value( true )
                ->set_help_text( 'Marque para exibir este logo em dispositivos móveis' )
        ) ),

    // Seção Final - Copyright, CNPJ e Endereço
    Field::make( 'separator', 'footer_final_separator', __( 'Informações Finais' ) ),
    Field::make( 'textarea', 'footer_copyright', __( 'Texto de Copyright' ) )
        ->set_default_value( '© 2024 Advanced Corretora. Todos os direitos reservados.' ),
    Field::make( 'text', 'footer_cnpj', __( 'CNPJ' ) )
        ->set_help_text( 'CNPJ da empresa' ),
    Field::make( 'rich_text', 'footer_address', __( 'Endereço com Link' ) )
        ->set_help_text( 'Endereço da empresa. Pode incluir HTML para links.' )
);
