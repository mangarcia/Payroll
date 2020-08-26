<?php

class View
{
  private $_controlador;
  private $_js;
  public $_layoutParams = [];

  public function __construct(Request $peticion)
  {
    $this->_controlador = $peticion->getControlador();
    $this->_js = array();
  }


  public function renderizar_elemento($vista)
  {
    if (Session::get('autenticado'))
    {
      require_once DOCUMENT_ROOT . '/views/' . $vista . '.phtml';
    }
  }


  public function renderizar($vista, $viewFolder = false, $showHeader = true)
  {
    $folder = $viewFolder ? $viewFolder : $this->_controlador;
    $folderPath = ROOT . 'views' . DS . $folder . DS;
    $viewPath = "{$folderPath}{$vista}.phtml";
    $viewJs = "/views/{$folder}/js/{$vista}.js";
    
    array_push($this->_js, $viewJs);
    $this->_layoutParams['js'] = $this->_js;
    $this->renderizarView($viewPath, $showHeader);
  }

  private function renderizarView ($ruta, $showHeader)
  {
    if (!is_readable($ruta))
    {
        throw new Exception("Error de vista {$ruta}");
        return false;
    }
    
    if ($showHeader)
        include_once ROOT . 'views' .DS . 'layout' .DS . DEFAULT_LAYOUT . DS . 'header.php';

    require_once $ruta;

    if ($showHeader)
        include_once ROOT . 'views' .DS . 'layout' .DS . DEFAULT_LAYOUT . DS . 'footer.php';
    
  }


  public function setJs(array $js)
  {
    if (is_array($js) && count($js))
    {
      for ($i = 0; $i < count($js); $i++)
      {
        $this->_js[] = ROOT . 'views/' . $this->_controlador . '/js/' . $js[$i] . '.js';
      }
    }
    else { throw new Exception('Error de JS'); }
  }


  public function renderizarAjax($vista)
  {
    $rutaView = ROOT . 'views' . DS . $this->_controlador . DS . $vista . '.phtml';

    if (is_readable($rutaView)) { include_once $rutaView; }
    else { throw new Exception('Error de vista (no existe)'); }
  }
}