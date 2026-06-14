<?php
header('Content-Type: application/json');
include("include/connect.php"); // assumes $pdo is set here




if($action == 'barcode_add') {

    $barcode = $_POST['barcode'];

    $stmt = $pdo->prepare("SELECT * FROM products WHERE barcode=?");
    $stmt->execute([$barcode]);

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$product) {
        echo json_encode([
            'status' => 'error',
            'msg' => 'Barcode not found'
        ]);
        exit;
    }

    if($product['quantity'] <= 0) {
        echo json_encode([
            'status' => 'error',
            'msg' => 'Out of stock'
        ]);
        exit;
    }

    $_SESSION['cart'][$product['id']] =
        ($_SESSION['cart'][$product['id']] ?? 0) + 1;

    echo json_encode([
        'status' => 'success'
    ]);

    exit;
}

class MedicineAPI {
    private $pdo;
    private $method;
    private $input;
    private $action;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->input = json_decode(file_get_contents('php://input'), true) ?? [];
        $this->action = $this->method === 'GET' ? ($_GET['action'] ?? '') : ($this->input['action'] ?? '');
    }

    public function run() {
        try {
            switch ($this->method) {
                case 'GET':
                    $this->handleGet();
                    break;
                case 'POST':
                    $this->handlePost();
                    break;
                case 'PUT':
                    $this->handlePut();
                    break;
                case 'DELETE':
                    $this->handleDelete();
                    break;
                default:
                    throw new Exception('Method not allowed');
            }
        } catch (Exception $e) {
            $this->respond(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function handleGet() {
        switch ($this->action) {
            case 'get_stats':
                $this->getStats();
                break;
            case 'search_medicines':
                $this->searchMedicines();
                break;
            case 'get_medicine':
                $this->getMedicine();
                break;
            default:
                throw new Exception('Invalid action');
        }
    }

    private function handlePost() {
        switch ($this->action) {
            case 'add_medicine':
                $this->addMedicine();
                break;
            case 'delete_medicine':
                $this->deleteMedicinePost();
                break;
            case 'update_stock':
                $this->updateStock();
                break;
            default:
                throw new Exception('Invalid action');
        }
    }

    private function handlePut() {
        $this->updateMedicine();
    }

    private function handleDelete() {
        $id = $_GET['id'] ?? 0;
        if (!$id) throw new Exception('ID is required');
        $stmt = $this->pdo->prepare("DELETE FROM medicines WHERE id = ?");
        $result = $stmt->execute([$id]);
        if ($result) {
            $this->respond(['success' => true, 'message' => 'Medicine deleted successfully']);
        } else {
            $this->respond(['success' => false, 'message' => 'Failed to delete medicine']);
        }
    }

    private function getStats() {
        $stats = [
            'total_medicines' => $this->pdo->query("SELECT COUNT(*) FROM medicines")->fetchColumn(),
            'low_stock' => $this->pdo->query("SELECT COUNT(*) FROM medicines WHERE quantity <= reorder_level")->fetchColumn(),
            'expired' => $this->pdo->query("SELECT COUNT(*) FROM medicines WHERE expiry_date <= CURDATE()")->fetchColumn(),
            'categories' => $this->pdo->query("SELECT COUNT(DISTINCT category) FROM medicines")->fetchColumn()
        ];
        $this->respond(['success' => true, 'stats' => $stats]);
    }

    private function searchMedicines() {
        $query = $_GET['query'] ?? '';
        $stmt = $this->pdo->prepare("SELECT * FROM medicines WHERE name LIKE ? OR generic_name LIKE ? LIMIT 10");
        $stmt->execute(["%$query%", "%$query%"]);
        $medicines = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->respond(['success' => true, 'medicines' => $medicines]);
    }

    private function getMedicine() {
        $id = $_GET['id'] ?? 0;
        $stmt = $this->pdo->prepare("SELECT * FROM medicines WHERE id = ?");
        $stmt->execute([$id]);
        $medicine = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($medicine) {
            $this->respond(['success' => true, 'medicine' => $medicine]);
        } else {
            $this->respond(['success' => false, 'message' => 'Medicine not found']);
        }
    }

    private function addMedicine() {
        $errors = $this->validateMedicineData($this->input);
        if ($errors) {
            $this->respond(['success' => false, 'message' => implode('; ', $errors)]);
            return;
        }

        $sql = "INSERT INTO medicines (name, generic_name, category, manufacturer, dosage, quantity, price, reorder_level, expiry_date, supplier, description) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            $this->input['name'],
            $this->input['generic_name'],
            $this->input['category'],
            $this->input['manufacturer'],
            $this->input['dosage'],
            $this->input['quantity'],
            $this->input['price'],
            $this->input['reorder_level'],
            $this->input['expiry_date'],
            $this->input['supplier'],
            $this->input['description']
        ]);
        if ($result) {
            $this->respond(['success' => true, 'message' => 'Medicine added successfully', 'id' => $this->pdo->lastInsertId()]);
        } else {
            $this->respond(['success' => false, 'message' => 'Failed to add medicine']);
        }
    }

    private function deleteMedicinePost() {
        $id = $this->input['id'] ?? 0;
        if (!$id) {
            $this->respond(['success' => false, 'message' => 'ID is required']);
            return;
        }
        $stmt = $this->pdo->prepare("DELETE FROM medicines WHERE id = ?");
        $result = $stmt->execute([$id]);
        if ($result) {
            $this->respond(['success' => true, 'message' => 'Medicine deleted successfully']);
        } else {
            $this->respond(['success' => false, 'message' => 'Failed to delete medicine']);
        }
    }

    private function updateStock() {
        $id = $this->input['id'] ?? 0;
        $quantity = $this->input['quantity'] ?? 0;
        if (!$id) {
            $this->respond(['success' => false, 'message' => 'ID is required']);
            return;
        }
        $stmt = $this->pdo->prepare("UPDATE medicines SET quantity = ? WHERE id = ?");
        $result = $stmt->execute([$quantity, $id]);
        if ($result) {
            $this->respond(['success' => true, 'message' => 'Stock updated successfully']);
        } else {
            $this->respond(['success' => false, 'message' => 'Failed to update stock']);
        }
    }

    private function updateMedicine() {
        $id = $this->input['id'] ?? 0;
        if (!$id) {
            $this->respond(['success' => false, 'message' => 'ID is required']);
            return;
        }

        $errors = $this->validateMedicineData($this->input);
        if ($errors) {
            $this->respond(['success' => false, 'message' => implode('; ', $errors)]);
            return;
        }

        $sql = "UPDATE medicines SET name = ?, generic_name = ?, category = ?, manufacturer = ?, 
                dosage = ?, quantity = ?, price = ?, reorder_level = ?, expiry_date = ?, 
                supplier = ?, description = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            $this->input['name'],
            $this->input['generic_name'],
            $this->input['category'],
            $this->input['manufacturer'],
            $this->input['dosage'],
            $this->input['quantity'],
            $this->input['price'],
            $this->input['reorder_level'],
            $this->input['expiry_date'],
            $this->input['supplier'],
            $this->input['description'],
            $id
        ]);
        if ($result) {
            $this->respond(['success' => true, 'message' => 'Medicine updated successfully']);
        } else {
            $this->respond(['success' => false, 'message' => 'Failed to update medicine']);
        }
    }

    private function validateMedicineData($data) {
        $errors = [];
        if (empty($data['name'])) {
            $errors[] = 'Medicine name is required';
        }
        if (empty($data['category'])) {
            $errors[] = 'Category is required';
        }
        if (!isset($data['quantity']) || !is_numeric($data['quantity']) || $data['quantity'] < 0) {
            $errors[] = 'Valid quantity is required';
        }
        if (!isset($data['price']) || !is_numeric($data['price']) || $data['price'] < 0) {
            $errors[] = 'Valid price is required';
        }
        if (!empty($data['expiry_date']) && strtotime($data['expiry_date']) < time()) {
            $errors[] = 'Expiry date cannot be in the past';
        }
        return $errors;
    }

    private function respond($data) {
        echo json_encode($data);
        exit;
    }
}

// Run the API
$api = new MedicineAPI($pdo);
$api->run();




?>
