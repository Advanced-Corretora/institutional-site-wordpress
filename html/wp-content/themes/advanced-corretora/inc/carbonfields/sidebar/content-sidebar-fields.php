<?php
/**
 * Campos Carbon Fields para Sidebar Conteúdo
 * 
 * @package advanced-corretora
 */

use Carbon_Fields\Field;

return [
    Field::make('separator', 'content_sidebar_separator', __('Configurações da Sidebar Conteúdo', 'advanced-corretora')),
    
    Field::make('checkbox', 'enable_content_sidebar', __('Ativar Sidebar Conteúdo', 'advanced-corretora'))
        ->set_help_text('Marque para inserir a "Sidebar Conteúdo" dentro do conteúdo dos posts e páginas'),
    
    Field::make('text', 'content_sidebar_paragraph', __('Inserir Após o Parágrafo', 'advanced-corretora'))
        ->set_attribute('type', 'number')
        ->set_attribute('min', 1)
        ->set_attribute('max', 10)
        ->set_default_value(2)
        ->set_conditional_logic([
            [
                'field' => 'enable_content_sidebar',
                'value' => true,
            ]
        ])
        ->set_help_text('Número do parágrafo após o qual a sidebar será inserida (padrão: 2)'),
    
    Field::make('textarea', 'content_sidebar_info', __('Instruções de Uso', 'advanced-corretora'))
        ->set_default_value('1. Ative a opção "Ativar Sidebar Conteúdo" acima
2. Defina após qual parágrafo a sidebar deve aparecer  
3. Vá em Aparência → Widgets
4. Adicione widgets na área "Sidebar Conteúdo"
5. A sidebar aparecerá automaticamente nos posts e páginas

Nota: A sidebar só aparecerá se tiver widgets adicionados e estiver ativada.')
        ->set_attribute('readOnly', true)
        ->set_conditional_logic([
            [
                'field' => 'enable_content_sidebar',
                'value' => true,
            ]
        ])
        ->set_help_text('Instruções para configurar a Sidebar Conteúdo'),
];
