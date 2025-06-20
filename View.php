<?php    

    declare(strict_types=1);

    namespace App; 

    class View {
        
        public function __construct(protected string $view, protected array $params = [] )
        {
            
        }

        public static function make(string $view, array $params = []): static {
            return new static($view, $params);
        }

        public function render(): string {

            $viewPath = VIEW_PATH . '/' . $this->view . '.php';            

            if(! file_exists($viewPath)) {
                throw new ViewNotFoundException();
            }

            // foreach($this->params as $key => $value) {
            //     $$key = $value;
            // }
            extract($this->params);

            ob_start();

            include $viewPath;
            // views/index.php

            return (string) ob_get_clean();

        }

        public function __toString()
        {
            return $this->render();
        }

        public function __get(string $name)
        {
            return $this->params[$name] ?? null;
        }
    }

?>