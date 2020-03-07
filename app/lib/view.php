<?php

// https://github.com/tommymarshall/layouts-with-views


class view {
	private $config;
	private $layout;
	private $vars;
	private $view;
	private $content;
        
        public $language = 'fr_FR';

	public function __construct( $config ) {
            $this->config = $config;
            $this->layout = $this->config['default_layout'];
	}
        
        public function createUrl($slug) {
            return /*'/' . substr($this->language, 0, 2) .*/ '/' . $slug;
        }

	public function getContent() {
            echo $this->content;
	}

	public function layout( $layout, $vars = array() ) {
            foreach ($vars as $var => $val ) {
                    $this->vars[$var] = $val;
            }
            $this->layout = $layout;
	}

	public function display($view, $vars = array()) {
            $this->view = $view ;
            $parts = explode('/', $this->view );

            foreach ($vars as $var => $val ) {
                    $$var = $val;
            }

            // If this is a nested view (in a sub-folder)
            if ( $parts ) {
                $this->view = '';

                foreach ($parts as $part) {
                    if ( $part['0'] && is_dir($this->config['app_dir'] . $this->config['view_path'] . $this->view . $part) ) {
                            $this->view .= $part . '/';
                    } else if ($part !== $this->config['default_view'] ){
                            $this->view .= $part;
                    } else {
                            $this->view .= $this->config['default_view'];
                    }
                }
            }

            if ( !file_exists($this->config['view_path'] . $this->view . '.html') )
                    die( "Could not load view <b>{$this->view}</b>" );

            // Grab contents of assigned view
            ob_start();
            require_once $this->config['view_path'] . $this->view . '.html';
            $this->content = ob_get_clean();

            // Get variables being sent to Layout
            foreach ($this->vars as $var => $val ) {
                $$var = $val;
            }

            if ( $this->layout ) {
                if ( !file_exists($this->config['layout_path'] . $this->layout . '.html') )
                        die( "Could not load layout <b>{$this->layout}</b>" );

                // Output out layout, with the page content and any extra variables
                require_once $this->config['layout_path'] . $this->layout . '.html';
            } else {
                    $this->getContent();
            }
	}
}