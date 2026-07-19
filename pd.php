<?php
// ===== 1) ดึงไฟล์ที่ต้องใช้เข้ามา =====
require 'db.php';      // ได้ตัวแปร $pdo มาใช้เชื่อมต่อ DB
require 'Food.php';    // ได้ class Food มาใช้
require 'Recipe.php';  // ได้ class Recipe มาใช้ (ต้อง require ก่อนใช้ constructor ของ Food)

// ===== 2) ยิง query ไปดึง "รายการอาหารทั้งหมด" จากตาราง foods =====
$stmt = $pdo->query("SELECT * FROM foods");
$foodsData = $stmt->fetchAll(); 
// ตอนนี้ $foodsData คือ array ดิบๆ จาก DB เช่น
// [ ['id'=>1,'name'=>'ผัดไทย','type'=>'...'], ['id'=>2,...] ]
// ยังไม่ใช่ object Food นะครับ แค่ข้อมูลดิบ

$foods = []; // ตัวแปรไว้เก็บ object Food ที่จะสร้างขึ้นจริง

// ===== 3) วนลูปทีละเมนู เพื่อแปลงข้อมูลดิบ ให้กลายเป็น object =====
foreach ($foodsData as $f) {

  // 3.1) ดึงวัตถุดิบของเมนูนี้ (WHERE food_id = เมนูนี้) จากตาราง recipes
  $stmtIng = $pdo->prepare("SELECT * FROM recipes WHERE food_id = :id");
  $stmtIng->execute(['id' => $f['id']]);
  $ingredientsData = $stmtIng->fetchAll();
  // ได้ array ดิบของวัตถุดิบ เช่น [['name'=>'กุ้ง','valor'=>100,'unit'=>'กรัม'], ...]

  // 3.2) แปลงวัตถุดิบดิบแต่ละชิ้น ให้กลายเป็น "object Recipe" จริงๆ
  //(ต้องแปลงก่อน เพราะ Food::add_recipe() บังคับรับเฉพาะ type Recipe เท่านั้น)
  $recipeObjects = [];
  foreach ($ingredientsData as $ing) {
    $recipeObjects[] = new Recipe($ing['name'], (int)$ing['valor'], $ing['unit']);
  }

  // 3.3) สร้าง object Food โดยยัด array ของ Recipe object เข้าไปตอน construct เลย
  //(ใช้ตาม constructor เดิมของพี่ที่รองรับ array $recipe อยู่แล้ว)
  $foods[] = new Food($f['name'], $f['type'], $recipeObjects);
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>รายการเมนูอาหาร</title>
  <style>
    /* จัดการสไตล์ภาพรวมของหน้าเว็บ */
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
    
    /* 🚀 หัวใจหลัก: สร้างระบบตารางตาราง 10 คอลัมน์แนวนอน */
    .menu-grid {
      display: grid;
      /* แบ่งเป็น 10 ช่องเท่าๆ กันใน 1 แถวแนวนอน */
      grid-template-columns: repeat(10, minmax(140px, 1fr)); 
      gap: 15px; /* ระยะห่างระหว่างกล่องเมนู */
      max-width: 100%;
      margin: 0 auto;
    }

    /* สไตล์ของกล่องเมนูแต่ละกล่อง (Food Card) */
    .food-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 12px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.37);
      transition: transform 0.2s;
      display: flex;
      flex-direction: column;
    }
    .food-card:hover {
      transform: translateY(-5px); /* เลื่อนขึ้นเล็กน้อยเวลาเอาเมาส์ชี้ */
      border-color: #b88a2f;
    }
    .food-card h3 {
      margin: 0 0 5px 0;
      font-size: 1rem;
      color: #2c3e50;
    }
    .food-type {
      font-size: 0.8rem;
      background: #f4ecd6;
      color: #886900;
      padding: 2px 6px;
      border-radius: 4px;
      align-self: flex-start;
      margin-bottom: 10px;
    }
    .recipe-list {
      font-size: 0.75rem;
      color: #666;
      border-top: 1px dashed #eee;
      padding-top: 8px;
      line-height: 1.4;
    }
  </style>
</head>
<body>

  <h1>รายการเมนูอาหารทั้งหมด</h1>

  <!-- กล่องครอบระบบตาราง -->
  <div class="menu-grid">
    <?php
    // ===== วนลูปแสดงผลจากอาร์เรย์ของ Object Food =====
    foreach ($foods as $food) {
      // พิมพ์ข้อความ HTML ที่คลาส Food ส่งกลับมา
      echo $food->get_details();
    }
    ?>
  </div>

</body>
</html>
  <?php
  // ===== 4) แสดงผล: เรียก get_details() ของแต่ละเมนู (method เดิมของพี่) =====
  foreach ($foods as $food) {
    $food->get_details();
  }
  ?>
</body>
</html>