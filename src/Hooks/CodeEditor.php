<?php
namespace WPX\Karambit\Hooks;
use WPX\Karambit\Manifest;
use WPX\Karambit\Core\Hooks\HookInterface;

class CodeEditor implements HookInterface {

    public function is_filter(): bool { return false ; }
    public function get_hook(): string { return 'admin_enqueue_scripts'; }
    public function get_priority(): int { return 10; }
    public function get_args_count(): int { return 1; }

    public function get_callback(): callable {
        return function() {
            // \WPX\Karambit\Core\Debug::logDump( Manifest::url('src/assets/css/public.css' ), __METHOD__ . ' Manifest::url(\'src/assets/css/public.css\' )');
            // Monaco Loader from CDN (or bundle it in your plugin)
            wp_enqueue_script('monaco-loader', 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs/loader.min.js', [], null, true);

            // Your init script
            wp_add_inline_script('monaco-loader', "
                require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs' }});
                require(['vs/editor/editor.main'], function() {
                    var target = document.getElementById('krmbt-blade-data');
                    if (!target) return;

                    var editor = monaco.editor.create(document.getElementById('krmbt-blade-editor'), {
                        value: target.value,
                        language: 'php', // Set to PHP for Blade-ish highlighting
                        theme: 'vs-dark',
                        automaticLayout: true
                    });

                    // Sync Monaco content to the hidden textarea before save
                    editor.getModel().onDidChangeContent(() => {
                        
                        target.value = editor.getValue();
                        
                    });
                });
            ");
            
            // Simple styling to make the editor look like a real IDE
            wp_add_inline_style('wp-admin', "#krmbt-blade-editor { height: 500px; border: 1px solid #ccd0d4; } #krmbt-blade-data { display:none; }");
        };
    }
}