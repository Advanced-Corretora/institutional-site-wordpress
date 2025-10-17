<?php
/**
 * Campos Carbon Fields para Hero de Páginas
 * 
 * @package advanced-corretora
 */

use Carbon_Fields\Field;

return [
    Field::make('separator', 'page_hero_separator', __('Configurações do Hero da Página', 'advanced-corretora')),
    
    Field::make('image', 'page_hero_background', __('Imagem de Fundo do Hero', 'advanced-corretora'))
        ->set_help_text('Imagem que aparecerá como fundo no hero da página. Recomendado: 1920x550px. Se não definida, será usado um gradiente.'),
    
    Field::make('text', 'page_hero_overlay', __('Intensidade do Overlay', 'advanced-corretora'))
        ->set_default_value('medium')
        ->set_help_text('Opções: light, medium, dark, heavy. Intensidade da sobreposição escura sobre a imagem de fundo'),
    
    Field::make('color', 'page_hero_gradient_start', __('Cor Inicial do Gradiente', 'advanced-corretora'))
        ->set_default_value('#003366')
        ->set_help_text('Cor inicial do gradiente (usado quando não há imagem de fundo)'),
    
    Field::make('color', 'page_hero_gradient_end', __('Cor Final do Gradiente', 'advanced-corretora'))
        ->set_default_value('#00B3E8')
        ->set_help_text('Cor final do gradiente (usado quando não há imagem de fundo)'),
    
    Field::make('textarea', 'page_custom_description', __('Descrição Personalizada', 'advanced-corretora'))
        ->set_help_text('Descrição que aparecerá no hero. Se não definida, será usado o excerpt da página.'),
];
