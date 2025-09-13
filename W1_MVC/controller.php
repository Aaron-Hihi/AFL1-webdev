<?php
session_start();
include("model.php");

$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'uc_lite';

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- DATABASE CONTROLLER ---
class DatabaseController {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    // READ

    public function getAllMahasiswa() {
        $mahasiswaList = [];
        $sql = "SELECT * FROM mahasiswa";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $mhs = new Mahasiswa();
                $mhs->id = $row['id'];
                $mhs->nama = $row['nama'];
                $mhs->address = $row['alamat'];
                $mahasiswaList[] = $mhs;
            }
        }
        return $mahasiswaList;
    }

    public function getAllMataKuliah() {
        $mataKuliahList = [];
        $sql = "SELECT * FROM mata_kuliah";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $mk = new MataKuliah();
                $mk->id = $row['id'];
                $mk->nama = $row['nama'];
                $mataKuliahList[] = $mk;
            }
        }
        return $mataKuliahList;
    }

    public function getAllEnrollments() {
        $enrollmentList = [];
        $sql = "SELECT * FROM enrollments";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $en = new Enrollment();
                $en->id = $row['id'];
                $en->id_mahasiswa = $row['id_mahasiswa'];
                $en->id_mata_kuliah = $row['id_mata_kuliah'];
                $enrollmentList[] = $en;
            }
        }
        return $enrollmentList;
    }

    // CREATE, UPDATE, DELETE

    public function addMahasiswa($nama, $address) {
        $stmt = $this->conn->prepare("INSERT INTO mahasiswa (nama, alamat) VALUES (?, ?)");
        $stmt->bind_param("ss", $nama, $address);
        $stmt->execute();
        $stmt->close();
    }
    
    public function editMahasiswa($id, $nama, $address) {
        $stmt = $this->conn->prepare("UPDATE mahasiswa SET nama = ?, alamat = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nama, $address, $id);
        $stmt->execute();
        $stmt->close();
    }

    public function deleteMahasiswa($id) {
        $stmt = $this->conn->prepare("DELETE FROM mahasiswa WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    public function addMataKuliah($nama) {
        $stmt = $this->conn->prepare("INSERT INTO mata_kuliah (nama) VALUES (?)");
        $stmt->bind_param("s", $nama);
        $stmt->execute();
        $stmt->close();
    }
    
    public function editMataKuliah($id, $nama) {
        $stmt = $this->conn->prepare("UPDATE mata_kuliah SET nama = ? WHERE id = ?");
        $stmt->bind_param("si", $nama, $id);
        $stmt->execute();
        $stmt->close();
    }

    public function deleteMataKuliah($id) {
        $stmt = $this->conn->prepare("DELETE FROM mata_kuliah WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    
    public function addEnrollment($id_mahasiswa, $id_mata_kuliah) {
        $checkStmt = $this->conn->prepare("SELECT id FROM enrollments WHERE id_mahasiswa = ? AND id_mata_kuliah = ?");
        $checkStmt->bind_param("ii", $id_mahasiswa, $id_mata_kuliah);
        $checkStmt->execute();
        $checkStmt->store_result();
        
        if ($checkStmt->num_rows === 0) {
            $stmt = $this->conn->prepare("INSERT INTO enrollments (id_mahasiswa, id_mata_kuliah) VALUES (?, ?)");
            $stmt->bind_param("ii", $id_mahasiswa, $id_mata_kuliah);
            $stmt->execute();
            $stmt->close();
        }
        $checkStmt->close();
    }
    
    public function deleteEnrollment($id) {
        $stmt = $this->conn->prepare("DELETE FROM enrollments WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

// FORM/REQUEST HANDLING

// Agar conn bisa dipakai berulang kali
$controller = new DatabaseController($conn);

$action = $_POST['action'] ?? null;

if ($action) {
    switch ($action) {
        // Mahasiswa
        case 'add_mahasiswa':
            if (isset($_POST['nama'], $_POST['address'])) {
                $controller->addMahasiswa($_POST['nama'], $_POST['address']);
            }
            break;
        case 'edit_mahasiswa':
            if (isset($_POST['id_mahasiswa'], $_POST['nama'], $_POST['address'])) {
                $controller->editMahasiswa($_POST['id_mahasiswa'], $_POST['nama'], $_POST['address']);
            }
            break;
        case 'delete_mahasiswa':
            if (isset($_POST['id'])) {
                $controller->deleteMahasiswa($_POST['id']);
            }
            break;
            
        // Mata Kuliah
        case 'add_matakuliah':
            if (isset($_POST['mata_kuliah'])) {
                $controller->addMataKuliah($_POST['mata_kuliah']);
            }
            break;
        case 'edit_matakuliah':
            if (isset($_POST['id_mata_kuliah'], $_POST['mata_kuliah'])) {
                $controller->editMataKuliah($_POST['id_mata_kuliah'], $_POST['mata_kuliah']);
            }
            break;
        case 'delete_matakuliah':
            if (isset($_POST['id'])) {
                $controller->deleteMataKuliah($_POST['id']);
            }
            break;


        // Enrollments
        case 'add_enrollment':
            if (isset($_POST['id_mahasiswa'], $_POST['id_mata_kuliah'])) {
                $controller->addEnrollment($_POST['id_mahasiswa'], $_POST['id_mata_kuliah']);
            }
            break;
        case 'delete_enrollment':
            if (isset($_POST['id'])) {
                $controller->deleteEnrollment($_POST['id']);
            }
            break;
    }
    
    header("Location: view.php");
    exit();
}

// VIEW NEEDS
$_SESSION['mahasiswaList'] = $controller->getAllMahasiswa();
$_SESSION['mataKuliahList'] = $controller->getAllMataKuliah();
$_SESSION['enrollmentList'] = $controller->getAllEnrollments();

$allMahasiswa = $_SESSION['mahasiswaList'] ?? [];
$allMataKuliah = $_SESSION['mataKuliahList'] ?? [];
$allEnrollments = $_SESSION['enrollmentList'] ?? [];

$mahasiswaMap = [];
foreach ($allMahasiswa as $m) {
    $mahasiswaMap[$m->id] = $m->nama;
}

$mataKuliahMap = [];
foreach ($allMataKuliah as $mk) {
    $mataKuliahMap[$mk->id] = $mk->nama;
}

?>