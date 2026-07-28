<?php
// เปิดแจ้งเตือน Error ไว้ เพื่อให้เห็นจุดผิดพลาดชัดเจนเวลารัน
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ===== 1) ดึงไฟล์ที่ต้องใช้เข้ามา =====
require_once 'db.php';     // เชื่อมต่อ DB ($pdo)
require_once 'Recipe.php'; // require Recipe ก่อน Food
require_once 'Food.php';   // ได้ class Food มาใช้

// ===== 2) ดึงข้อมูลด้วย SQL JOIN (แก้ชื่อคอลัมน์ของทั้ง foods และ recipes ให้ตรง DB แล้ว) =====
$sql = "SELECT 
            f.id AS food_id, 
            f.name AS food_name, 
            f.type AS food_type, 
            r.name AS recipe_name, 
            r.valor AS recipe_valor, 
            r.unit AS recipe_unit
        FROM foods f
        LEFT JOIN recipes r ON f.id = r.food_id
        ORDER BY f.id ASC";

$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll();

// ===== 3) จัดกลุ่มข้อมูลตาม Food ID =====
$groupedData = [];
foreach ($rows as $row) {
    $foodId = $row['food_id'];

    if (!isset($groupedData[$foodId])) {
        $groupedData[$foodId] = [
            'name' => $row['food_name'],
            'type' => $row['food_type'],
            'recipes' => []
        ];
    }

    // สร้าง Object Recipe ถ้ามีวัตถุดิบ
    if (!empty($row['recipe_name'])) {
        $groupedData[$foodId]['recipes'][] = new Recipe(
            $row['recipe_name'],
            (int)$row['recipe_valor'],
            $row['recipe_unit']
        );
    }
}

// ===== 4) แปลงเป็น Object Food =====
$foods = [];
foreach ($groupedData as $data) {
    $foods[] = new Food($data['name'], $data['type'], $data['recipes']);
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>รายการเมนูอาหาร</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f9f9fb;
      color: #333;
      margin: 0;
      padding: 20px;
    }
    h1 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 30px;
    }
    .menu-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); 
      gap: 15px; 
      max-width: 1200px;
      margin: 0 auto;
    }
    .food-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 15px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      transition: transform 0.2s, border-color 0.2s;
      display: flex;
      flex-direction: column;
    }
    .food-card:hover {
      transform: translateY(-5px); 
      border-color: #b88a2f;
    }
    .food-card h3 {
      margin: 0 0 5px 0;
      font-size: 1.1rem;
      color: #2c3e50;
    }
    .food-type {
      font-size: 0.8rem;
      background: #f4ecd6;
      color: #886900;
      padding: 3px 8px;
      border-radius: 4px;
      align-self: flex-start;
      margin-bottom: 10px;
      font-weight: bold;
    }
    .recipe-list {
      font-size: 0.85rem;
      color: #555;
      border-top: 1px dashed #eee;
      padding-top: 8px;
      line-height: 1.6;
    }
  </style>
</head>
<body>

  <h1>รายการเมนูอาหารทั้งหมด</h1>

  <div class="menu-grid">
    <?php
    foreach ($foods as $food) {
      echo $food->get_details();
    }
    ?>
  </div>

</body>
</html>