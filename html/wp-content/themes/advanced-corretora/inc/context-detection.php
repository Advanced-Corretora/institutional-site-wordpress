<?php
/**
 * Context Detection Functions
 * Funções para detectar contexto da instalação (blog vs institucional)
 *
 * @package advanced-corretora
 */

/**
 * Detecta se estamos no contexto do blog
 * 
 * @return bool True se for contexto do blog
 */
function advanced_corretora_is_blog_context() {
    // 🚀 CONTROLE POR PARÂMETRO - Para desenvolvimento/testes
    if (isset($_GET['context'])) {
        $forced_context = sanitize_text_field($_GET['context']);
        if ($forced_context === 'blog') {
            return true;
        } elseif ($forced_context === 'institutional') {
            return false;
        }
    }
    
    // 🚀 CONTROLE POR COOKIE - Persiste a escolha
    if (isset($_COOKIE['advanced_corretora_context'])) {
        $cookie_context = sanitize_text_field($_COOKIE['advanced_corretora_context']);
        if ($cookie_context === 'blog') {
            return true;
        } elseif ($cookie_context === 'institutional') {
            return false;
        }
    }
    
    $current_url = home_url();
    
    // Detecta por URL
    $is_blog_url = (
        strpos($current_url, '/blog2/') !== false || 
        strpos($current_url, 'blog.') !== false ||
        strpos($current_url, '-blog.') !== false
    );
    
    // Detecta por opção do WordPress (fallback)
    $context_option = get_option('site_context', 'institutional');
    $is_blog_option = ($context_option === 'blog');
    
    return $is_blog_url || $is_blog_option;
}

/**
 * Detecta se estamos no contexto institucional
 * 
 * @return bool True se for contexto institucional
 */
function advanced_corretora_is_institutional_context() {
    return !advanced_corretora_is_blog_context();
}

/**
 * Retorna o contexto atual como string
 * 
 * @return string 'blog' ou 'institutional'
 */
function advanced_corretora_get_context() {
    return advanced_corretora_is_blog_context() ? 'blog' : 'institutional';
}

/**
 * Adiciona classe CSS baseada no contexto
 * 
 * @param array $classes Classes existentes
 * @return array Classes com contexto adicionado
 */
function advanced_corretora_add_context_body_class($classes) {
    $context = advanced_corretora_get_context();
    $classes[] = 'context-' . $context;
    
    return $classes;
}
add_filter('body_class', 'advanced_corretora_add_context_body_class');

/**
 * Retorna configurações específicas do contexto
 * 
 * @return array Configurações do contexto atual
 */
function advanced_corretora_get_context_config() {
    $context = advanced_corretora_get_context();
    
    $configs = array(
        'blog' => array(
            'search_placeholder' => 'Buscar no blog...',
            'search_title' => 'Busca no Blog',
            'no_results_message' => 'Nenhum post encontrado',
            'pagination_prev' => '← Posts Anteriores',
            'pagination_next' => 'Próximos Posts →',
            'layout_style' => 'cards',
            'show_reading_time' => true,
            'show_categories' => true,
            'show_date' => true,
        ),
        'institutional' => array(
            'search_placeholder' => 'Buscar...',
            'search_title' => 'Resultado de busca',
            'no_results_message' => 'Nenhum resultado encontrado',
            'pagination_prev' => '← Anterior',
            'pagination_next' => 'Próximo →',
            'layout_style' => 'list',
            'show_reading_time' => false,
            'show_categories' => false,
            'show_date' => false,
        )
    );
    
    return $configs[$context];
}

/**
 * Enfileira estilos específicos do contexto
 */
function advanced_corretora_enqueue_context_styles() {
    $context = advanced_corretora_get_context();
    
    if ($context === 'blog') {
        // Estilos específicos do blog já estão no _search.scss
        // Mas podemos adicionar mais se necessário
    }
}
add_action('wp_enqueue_scripts', 'advanced_corretora_enqueue_context_styles');

/**
 * Adiciona variáveis CSS baseadas no contexto
 */
function advanced_corretora_context_css_vars() {
    $context = advanced_corretora_get_context();
    
    $css_vars = '';
    
    if ($context === 'blog') {
        $css_vars = '
        :root {
            --context-primary-color: #00B3E8;
            --context-secondary-color: #003366;
            --context-background: #f8f9fa;
            --context-card-radius: 16px;
            --context-button-radius: 25px;
        }';
    } else {
        $css_vars = '
        :root {
            --context-primary-color: #003366;
            --context-secondary-color: #00B3E8;
            --context-background: #ffffff;
            --context-card-radius: 12px;
            --context-button-radius: 8px;
        }';
    }
    
    echo '<style type="text/css">' . $css_vars . '</style>';
}
add_action('wp_head', 'advanced_corretora_context_css_vars');

/**
 * Shortcode para exibir conteúdo baseado no contexto
 * 
 * Uso: [context_content blog="Conteúdo do blog" institutional="Conteúdo institucional"]
 */
function advanced_corretora_context_content_shortcode($atts) {
    $atts = shortcode_atts(array(
        'blog' => '',
        'institutional' => '',
    ), $atts);
    
    $context = advanced_corretora_get_context();
    
    return $atts[$context];
}
add_shortcode('context_content', 'advanced_corretora_context_content_shortcode');

/**
 * Função para debug - mostra informações do contexto atual
 * Só funciona para usuários logados com capacidade de editar temas
 */
function advanced_corretora_debug_context() {
    if (!current_user_can('edit_themes')) {
        return;
    }
    
    if (isset($_GET['debug_context'])) {
        $context = advanced_corretora_get_context();
        $config = advanced_corretora_get_context_config();
        $current_url = home_url();
        
        echo '<div style="position: fixed; top: 10px; right: 10px; background: #000; color: #fff; padding: 10px; border-radius: 5px; z-index: 9999; font-size: 12px;">';
        echo '<strong>Context Debug:</strong><br>';
        echo 'Context: ' . $context . '<br>';
        echo 'URL: ' . $current_url . '<br>';
        echo 'Config: ' . json_encode($config, JSON_PRETTY_PRINT);
        echo '</div>';
    }
}
add_action('wp_footer', 'advanced_corretora_debug_context');

/**
 * 🚀 SWITCHER DE CONTEXTO - Para desenvolvimento
 * Adiciona um switcher visual para alternar entre contextos
 */
function advanced_corretora_context_switcher() {
    // Só mostra para usuários logados ou em desenvolvimento
    if (!current_user_can('edit_themes') && !WP_DEBUG) {
        return;
    }
    
    $current_context = advanced_corretora_get_context();
    $current_url = isset($_SERVER['REQUEST_URI']) ? remove_query_arg(['context'], $_SERVER['REQUEST_URI']) : '';
    
    ?>
    <div id="context-switcher" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999; background: white; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); padding: 15px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
        <div style="font-weight: 600; margin-bottom: 10px; color: #333; font-size: 14px;">
            🎨 Context Switcher
        </div>
        
        <div style="display: flex; gap: 8px; margin-bottom: 10px;">
            <button onclick="switchContext('institutional')" 
                    style="padding: 6px 12px; border: 2px solid #003366; border-radius: 20px; background: <?php echo $current_context === 'institutional' ? '#003366' : 'white'; ?>; color: <?php echo $current_context === 'institutional' ? 'white' : '#003366'; ?>; cursor: pointer; font-size: 12px; font-weight: 500;">
                🏢 Institucional
            </button>
            
            <button onclick="switchContext('blog')" 
                    style="padding: 6px 12px; border: 2px solid #00B3E8; border-radius: 20px; background: <?php echo $current_context === 'blog' ? '#00B3E8' : 'white'; ?>; color: <?php echo $current_context === 'blog' ? 'white' : '#00B3E8'; ?>; cursor: pointer; font-size: 12px; font-weight: 500;">
                📝 Blog
            </button>
        </div>
        
        <div style="font-size: 11px; color: #666;">
            Atual: <strong><?php echo ucfirst($current_context); ?></strong>
        </div>
        
        <div style="margin-top: 8px; font-size: 10px; color: #999;">
            <a href="<?php echo add_query_arg('debug_context', '1'); ?>" style="color: #999; text-decoration: none;">🐛 Debug Info</a>
        </div>
    </div>

    <script>
    function switchContext(context) {
        // Define cookie para persistir
        document.cookie = `advanced_corretora_context=${context}; path=/; max-age=86400`;
        
        // Recarrega a página com o parâmetro
        const url = new URL(window.location);
        url.searchParams.set('context', context);
        window.location.href = url.toString();
    }
    
    // Adiciona atalhos de teclado
    document.addEventListener('keydown', function(e) {
        // Ctrl + Shift + B = Blog
        if (e.ctrlKey && e.shiftKey && e.key === 'B') {
            e.preventDefault();
            switchContext('blog');
        }
        
        // Ctrl + Shift + I = Institutional
        if (e.ctrlKey && e.shiftKey && e.key === 'I') {
            e.preventDefault();
            switchContext('institutional');
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'advanced_corretora_context_switcher');

/**
 * 🚀 AJAX Handler para mudança de contexto
 */
function advanced_corretora_ajax_switch_context() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'context_switch_nonce')) {
        wp_die('Security check failed');
    }
    
    if (!isset($_POST['context'])) {
        wp_send_json_error('Context not provided');
        return;
    }
    
    $context = sanitize_text_field($_POST['context']);
    
    if (in_array($context, ['blog', 'institutional'])) {
        setcookie('advanced_corretora_context', $context, time() + DAY_IN_SECONDS, '/');
        wp_send_json_success(['context' => $context]);
    } else {
        wp_send_json_error('Invalid context');
    }
}
add_action('wp_ajax_switch_context', 'advanced_corretora_ajax_switch_context');
add_action('wp_ajax_nopriv_switch_context', 'advanced_corretora_ajax_switch_context');
