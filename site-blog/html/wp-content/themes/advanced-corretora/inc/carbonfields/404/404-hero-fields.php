<?php
/**
 * Campos Carbon Fields para Hero da Página 404
 * 
 * @package advanced-corretora
 */

use Carbon_Fields\Field;

return [
    Field::make('separator', '404_hero_separator', __('Configurações do Hero da Página 404', 'advanced-corretora')),
    
    Field::make('image', '404_hero_background', __('Imagem de Fundo do Hero', 'advanced-corretora'))
        ->set_help_text('Imagem que aparecerá como fundo no hero da página 404. Recomendado: 1920x550px. Se não definida, será usado um gradiente.'),
    
    Field::make('text', '404_hero_overlay', __('Intensidade do Overlay', 'advanced-corretora'))
        ->set_default_value('medium')
        ->set_help_text('Opções: light, medium, dark, heavy. Intensidade da sobreposição escura sobre a imagem de fundo'),
    
    Field::make('color', '404_hero_gradient_start', __('Cor Inicial do Gradiente', 'advanced-corretora'))
        ->set_default_value('#003366')
        ->set_help_text('Cor inicial do gradiente (usado quando não há imagem de fundo)'),
    
    Field::make('color', '404_hero_gradient_end', __('Cor Final do Gradiente', 'advanced-corretora'))
        ->set_default_value('#00B3E8')
        ->set_help_text('Cor final do gradiente (usado quando não há imagem de fundo)'),
];
