<?php
/**
 * Required and recommended plugins for Advanced Corretora theme
 *
 * This file demonstrates how to include and require plugins with your theme.
 * Uses TGM Plugin Activation library.
 *
 * @package advanced-corretora
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once get_template_directory() . '/inc/tgm-plugin-activation/class-tgm-plugin-activation.php';

add_action('tgmpa_register', 'advanced_corretora_register_required_plugins');

/**
 * Register the required plugins for this theme.
 */
function advanced_corretora_register_required_plugins()
{
    /*
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(
        // Plugins obrigatórios
        // array(
        //     'name'      => 'Carbon Fields',
        //     'slug'      => 'carbon-fields',
        //     'required'  => true,
        //     'version'   => '3.6.0',
        // ),
        
        // array(
        //     'name'      => 'Advanced Custom Fields',
        //     'slug'      => 'advanced-custom-fields',
        //     'required'  => true,
        // ),

        // Plugins recomendados
        array(
            'name'      => 'Yoast SEO',
            'slug'      => 'wordpress-seo',
            'required'  => false,
        ),

        array(
            'name'      => 'Contact Form 7',
            'slug'      => 'contact-form-7',
            'required'  => true,
        ),

        // array(
        //     'name'      => 'WP Super Cache',
        //     'slug'      => 'wp-super-cache',
        //     'required'  => false,
        // ),

        // array(
        //     'name'      => 'Wordfence Security',
        //     'slug'      => 'wordfence',
        //     'required'  => false,
        // ),

        // Exemplo de plugin personalizado (se você tiver)
        /*
        array(
            'name'               => 'Custom Plugin Name',
            'slug'               => 'custom-plugin-slug',
            'source'             => get_template_directory() . '/plugins/custom-plugin.zip',
            'required'           => true,
            'version'            => '1.0.0',
            'force_activation'   => false,
            'force_deactivation' => false,
            'external_url'       => '',
        ),
        */
    );

    /*
     * Array of configuration settings.
     */
    $config = array(
        'id'           => 'advanced-corretora',
        'default_path' => '',
        'menu'         => 'tgmpa-install-plugins',
        'parent_slug'  => 'themes.php',
        'capability'   => 'edit_theme_options',
        'has_notices'  => true,
        'dismissable'  => true,
        'dismiss_msg'  => '',
        'is_automatic' => false,
        'message'      => '',
        'strings'      => array(
            'page_title'                      => __('Instalar Plugins Necessários', 'advanced-corretora'),
            'menu_title'                      => __('Instalar Plugins', 'advanced-corretora'),
            'installing'                      => __('Instalando Plugin: %s', 'advanced-corretora'),
            'updating'                        => __('Atualizando Plugin: %s', 'advanced-corretora'),
            'oops'                            => __('Algo deu errado com a API do plugin.', 'advanced-corretora'),
            'notice_can_install_required'     => _n_noop(
                'Este tema requer o seguinte plugin: %1$s.',
                'Este tema requer os seguintes plugins: %1$s.',
                'advanced-corretora'
            ),
            'notice_can_install_recommended'  => _n_noop(
                'Este tema recomenda o seguinte plugin: %1$s.',
                'Este tema recomenda os seguintes plugins: %1$s.',
                'advanced-corretora'
            ),
            'notice_ask_to_update'            => _n_noop(
                'O seguinte plugin precisa ser atualizado para sua versão mais recente para garantir máxima compatibilidade com este tema: %1$s.',
                'Os seguintes plugins precisam ser atualizados para suas versões mais recentes para garantir máxima compatibilidade com este tema: %1$s.',
                'advanced-corretora'
            ),
            'notice_ask_to_update_maybe'      => _n_noop(
                'Há uma atualização disponível para: %1$s.',
                'Há atualizações disponíveis para os seguintes plugins: %1$s.',
                'advanced-corretora'
            ),
            'notice_can_activate_required'    => _n_noop(
                'O seguinte plugin obrigatório está inativo: %1$s.',
                'Os seguintes plugins obrigatórios estão inativos: %1$s.',
                'advanced-corretora'
            ),
            'notice_can_activate_recommended' => _n_noop(
                'O seguinte plugin recomendado está inativo: %1$s.',
                'Os seguintes plugins recomendados estão inativos: %1$s.',
                'advanced-corretora'
            ),
            'install_link'                    => _n_noop(
                'Começar a instalar plugin',
                'Começar a instalar plugins',
                'advanced-corretora'
            ),
            'update_link'                     => _n_noop(
                'Começar a atualizar plugin',
                'Começar a atualizar plugins',
                'advanced-corretora'
            ),
            'activate_link'                   => _n_noop(
                'Começar a ativar plugin',
                'Começar a ativar plugins',
                'advanced-corretora'
            ),
            'return'                          => __('Retornar ao Instalador de Plugins Necessários', 'advanced-corretora'),
            'plugin_activated'                => __('Plugin ativado com sucesso.', 'advanced-corretora'),
            'activated_successfully'          => __('O seguinte plugin foi ativado com sucesso:', 'advanced-corretora'),
            'plugin_already_active'           => __('Nenhuma ação realizada. Plugin %1$s já estava ativo.', 'advanced-corretora'),
            'plugin_needs_higher_version'     => __('Plugin não ativado. Uma versão mais alta de %s é necessária para este tema. Por favor, atualize o plugin.', 'advanced-corretora'),
            'complete'                        => __('Todos os plugins instalados e ativados com sucesso. %1$s', 'advanced-corretora'),
            'dismiss'                         => __('Dispensar este aviso', 'advanced-corretora'),
            'notice_cannot_install_activate'  => __('Há um ou mais plugins obrigatórios ou recomendados para instalar, atualizar ou ativar.', 'advanced-corretora'),
            'contact_admin'                   => __('Por favor, entre em contato com o administrador deste site para obter ajuda.', 'advanced-corretora'),
            'nag_type'                        => '',
        ),
    );

    tgmpa($plugins, $config);
}

/**
 * Check if required plugins are active and show admin notice if not
 */
function advanced_corretora_check_required_plugins()
{
    $required_plugins = array(
        // 'carbon-fields/carbon-fields-plugin.php' => 'Carbon Fields',
        // 'advanced-custom-fields/acf.php' => 'Advanced Custom Fields',
        'contact-form-7/wp-contact-form-7.php' => 'Contact Form 7',
    );

    $inactive_plugins = array();

    foreach ($required_plugins as $plugin_path => $plugin_name) {
        if (!is_plugin_active($plugin_path)) {
            $inactive_plugins[] = $plugin_name;
        }
    }

    if (!empty($inactive_plugins)) {
        add_action('admin_notices', function () use ($inactive_plugins) {
            $plugin_list = implode(', ', $inactive_plugins);
            echo '<div class="notice notice-error is-dismissible">';
            echo '<p><strong>' . __('Atenção:', 'advanced-corretora') . '</strong> ';
            echo sprintf(
                _n(
                    'O seguinte plugin é obrigatório para o funcionamento correto do tema: %s',
                    'Os seguintes plugins são obrigatórios para o funcionamento correto do tema: %s',
                    count($inactive_plugins),
                    'advanced-corretora'
                ),
                $plugin_list
            );
            echo '</p>';
            echo '<p><a href="' . admin_url('themes.php?page=tgmpa-install-plugins') . '" class="button button-primary">';
            echo __('Instalar/Ativar Plugins', 'advanced-corretora');
            echo '</a></p>';
            echo '</div>';
        });
    }
}
add_action('admin_init', 'advanced_corretora_check_required_plugins');

/**
 * Add theme functionality check
 */
function advanced_corretora_theme_functionality_check()
{
    // Verificar se Carbon Fields está ativo
    if (!class_exists('Carbon_Fields\\Container')) {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-warning">';
            echo '<p><strong>' . __('Funcionalidade Limitada:', 'advanced-corretora') . '</strong> ';
            echo __('Alguns recursos do tema podem não funcionar corretamente sem o plugin Carbon Fields ativo.', 'advanced-corretora');
            echo '</p>';
            echo '</div>';
        });
    }

    // Verificar se ACF está ativo
    // if (!function_exists('get_field')) {
    //     add_action('admin_notices', function () {
    //         echo '<div class="notice notice-warning">';
    //         echo '<p><strong>' . __('Funcionalidade Limitada:', 'advanced-corretora') . '</strong> ';
    //         echo __('Campos personalizados podem não funcionar corretamente sem o plugin Advanced Custom Fields ativo.', 'advanced-corretora');
    //         echo '</p>';
    //         echo '</div>';
    //     });
    // }
}
add_action('after_setup_theme', 'advanced_corretora_theme_functionality_check');
