<?php

/**
 * Carbon Fields para Taxonomias (Categorias e Tags)
 * 
 * Campos personalizados para categorias e tags do blog
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

// Campos para Categorias
Container::make('term_meta', __('Configurações da Categoria', 'advanced-corretora'))
    ->where('term_taxonomy', '=', 'category')
    ->add_fields(array(
        
        // Hero Section
        Field::make('separator', 'category_hero_separator', __('Hero da Categoria', 'advanced-corretora')),
        
        Field::make('image', 'category_hero_background', __('Imagem de Fundo do Hero', 'advanced-corretora'))
            ->set_help_text('Imagem que aparecerá como fundo no hero da categoria. Recomendado: 1920x550px'),
        
        Field::make('text', 'category_hero_overlay', __('Intensidade do Overlay', 'advanced-corretora'))
            ->set_default_value('medium')
            ->set_help_text('Intensidade da sobreposição escura sobre a imagem de fundo (light, medium, dark, heavy)'),
        
        Field::make('color', 'category_hero_gradient_start', __('Cor Inicial do Gradiente', 'advanced-corretora'))
            ->set_default_value('#003366')
            ->set_help_text('Cor usada quando não há imagem de fundo'),
        
        Field::make('color', 'category_hero_gradient_end', __('Cor Final do Gradiente', 'advanced-corretora'))
            ->set_default_value('#00B3E8')
            ->set_help_text('Cor usada quando não há imagem de fundo'),
        
        // SEO e Conteúdo
        Field::make('separator', 'category_content_separator', __('Conteúdo da Categoria', 'advanced-corretora')),
        
        Field::make('textarea', 'category_custom_description', __('Descrição Personalizada', 'advanced-corretora'))
            ->set_help_text('Descrição que aparecerá no hero. Se vazio, usará a descrição padrão da categoria'),
        
        Field::make('text', 'category_posts_count_text', __('Texto Personalizado do Contador', 'advanced-corretora'))
            ->set_help_text('Ex: "artigos publicados", "posts disponíveis". Se vazio, usará "posts"')
            ->set_default_value('posts'),
    ));

// Campos para Tags
Container::make('term_meta', __('Configurações da Tag', 'advanced-corretora'))
    ->where('term_taxonomy', '=', 'post_tag')
    ->add_fields(array(
        
        // Hero Section
        Field::make('separator', 'tag_hero_separator', __('Hero da Tag', 'advanced-corretora')),
        
        Field::make('image', 'tag_hero_background', __('Imagem de Fundo do Hero', 'advanced-corretora'))
            ->set_help_text('Imagem que aparecerá como fundo no hero da tag. Recomendado: 1920x550px'),
        
        Field::make('text', 'tag_hero_overlay', __('Intensidade do Overlay', 'advanced-corretora'))
            ->set_default_value('medium')
            ->set_help_text('Intensidade da sobreposição escura sobre a imagem de fundo (light, medium, dark, heavy)'),
        
        Field::make('color', 'tag_hero_gradient_start', __('Cor Inicial do Gradiente', 'advanced-corretora'))
            ->set_default_value('#003366')
            ->set_help_text('Cor usada quando não há imagem de fundo'),
        
        Field::make('color', 'tag_hero_gradient_end', __('Cor Final do Gradiente', 'advanced-corretora'))
            ->set_default_value('#00B3E8')
            ->set_help_text('Cor usada quando não há imagem de fundo'),
        
        // SEO e Conteúdo
        Field::make('separator', 'tag_content_separator', __('Conteúdo da Tag', 'advanced-corretora')),
        
        Field::make('textarea', 'tag_custom_description', __('Descrição Personalizada', 'advanced-corretora'))
            ->set_help_text('Descrição que aparecerá no hero. Se vazio, usará a descrição padrão da tag'),
        
        Field::make('text', 'tag_posts_count_text', __('Texto Personalizado do Contador', 'advanced-corretora'))
            ->set_help_text('Ex: "artigos relacionados", "posts marcados". Se vazio, usará "posts"')
            ->set_default_value('posts'),
    ));
