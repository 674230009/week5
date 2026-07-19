<?php
class Recipe {
  // สร้างpropertie รับค่าชื่อ และ ประเภทวัตถุดิบ
  private string $name;
  private int $valor;
  private string $unit;

  // Constructor รับค่าชื่อ และ ประเภทอาหาร
  function __construct(string $name, int $valor, string $unit)
  {
    $this->name = $name;
    $this->valor = $valor;
    $this->unit = $unit;
  }

  //"ส่งข้อความ (String) รูปแบบรายการวัตถุดิบที่จัดฟอร์แมตสวยงามแล้ว กลับออกไปให้ฟังก์ชันอื่นนำไปใช้งานต่อ"
  function get_details(): string{
    return "&nbsp;&nbsp;- {$this->name} : {$this->valor} {$this->unit}<br>";
  }
}
