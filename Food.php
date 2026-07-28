<?php
require_once 'db.php';
class Food {
 
  private string $fname;
  private string $ftype;
  private array $recipe = [];

  
  function __construct(string $fname, string $ftype, array $recipe = [])
  {
    $this->fname = $fname;
    $this->ftype = $ftype;
    foreach ($recipe as $ing) {
      $this->add_recipe($ing);
    }
  }

  
  function add_recipe(Recipe $ing)
  {
    $this->recipe[] = $ing;
  }

  public function get_details(): string 
  {
    
    $html = "<div class='food-card'>";
    $html .= "<h3>" . htmlspecialchars($this->fname) . "</h3>";
    $html .= "<span class='food-type'>" . htmlspecialchars($this->ftype) . "</span>";
    $html .= "<div class='recipe-list'>";
    
   
    foreach ($this->recipe as $ing) {
      $html .= $ing->get_details(); 
    }
    
    $html .= "</div>"; 
    $html .= "</div>"; 
    
    return $html; 
  }
}
?>