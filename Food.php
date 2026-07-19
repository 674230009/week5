<?php
class Food {
 
  private string $name;
  private string $type;
  private array $recipe = [];

  
  function __construct(string $name, string $type, array $recipe = [])
  {
    $this->name = $name;
    $this->type = $type;
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
    $html .= "<h3>" . htmlspecialchars($this->name) . "</h3>";
    $html .= "<span class='food-type'>" . htmlspecialchars($this->type) . "</span>";
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