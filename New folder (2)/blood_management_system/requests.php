<?php
require_once 'config/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_request'])) {
    $patient_name = mysqli_real_escape_string($conn, $_POST['patient_name']);
    $blood_group = mysqli_real_escape_string($conn, $_POST['blood_group']);
    $units_needed = (int)$_POST['units_needed'];
    $hospital = mysqli_real_escape_string($conn, $_POST['hospital']);
    $urgency = mysqli_real_escape_string($conn, $_POST['urgency']);

    $sql = "INSERT INTO blood_requests (requester_id, patient_name, blood_group, units_needed, hospital, urgency, status) 
            VALUES ($user_id, '$patient_name', '$blood_group', $units_needed, '$hospital', '$urgency', 'Pending')";
    
    if ($conn->query($sql)) {
        $message = "Blood request submitted successfully!";
    }
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    $req_id = (int)$_GET['id'];
    $action = $_GET['action'];
    $new_status = ($action === 'approve') ? 'Approved' : (($action === 'dispatch') ? 'Dispatched' : (($action === 'reject') ? 'Rejected' : ''));

    if ($new_status) {
        $conn->query("UPDATE blood_requests SET status='$new_status' WHERE id=$req_id");
        header("Location: requests.php");
        exit();
    }
}

$requests = $conn->query("SELECT r.*, u.name as requester_name FROM blood_requests r JOIN users u ON r.requester_id = u.id ORDER BY r.id DESC");
?>

<h2>Blood Requests & Dispatch Management</h2>
<?php if($message) echo "<p style='color: #5cb85c; margin-bottom: 15px; font-weight: 500;'>$message</p>"; ?>

<div class="form-card" style="margin-bottom: 30px;">
    <h3>Create New Blood Request</h3>
    <form action="requests.php" method="POST">
        <div class="form-group">
            <label>Patient Name</label>
            <input type="text" name="patient_name" required>
        </div>
        <div class="form-group">
            <label>Blood Group Needed</label>
            <select name="blood_group" required>
                <optgroup label="Common Groups">
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                </optgroup>
                <optgroup label="Rare Groups">
                    <option value="Bombay Blood Group">Bombay Blood Group</option>
                    <option value="Rh-null">Rh-null</option>
                    <option value="Lutheran">Lutheran</option>
                    <option value="Duffy">Duffy</option>
                </optgroup>
            </select>
        </div>
        <div class="form-group">
            <label>Units Needed</label>
            <input type="number" name="units_needed" min="1" required>
        </div>
        <div class="form-group">
            <label>Hospital Name & Location</label>
            <input type="text" name="hospital" required>
        </div>
        <div class="form-group">
            <label>Urgency Level</label>
            <select name="urgency" required>
                <option value="Normal">Normal</option>
                <option value="Critical">Critical</option>
            </select>
        </div>
        <button type="submit" name="submit_request" class="btn">Submit Request</button>
    </form>
</div>

<h3>Active Requests & Dispatch Status Log</h3>
<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient / Requester</th>
                <th>Blood Group</th>
                <th>Units</th>
                <th>Hospital</th>
                <th>Urgency</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $requests->fetch_assoc()): ?>
            <tr>
                <td>#<?php echo $row['id']; ?></td>
                <td>
                    <strong><?php echo htmlspecialchars($row['patient_name']); ?></strong><br>
                    <small style="color: #666;">By: <?php echo htmlspecialchars($row['requester_name']); ?></small>
                </td>
                <td><strong style="color: #b71c1c;"><?php echo $row['blood_group']; ?></strong></td>
                <td><?php echo $row['units_needed']; ?></td>
                <td><?php echo htmlspecialchars($row['hospital']); ?></td>
                <td><span
                        style="color: <?php echo $row['urgency']=='Critical' ? '#d9534f' : '#333'; ?>; font-weight: bold;"><?php echo $row['urgency']; ?></span>
                </td>
                <td><span class="badge"
                        style="background: <?php echo ($row['status']=='Dispatched') ? '#0275d8' : (($row['status']=='Approved') ? '#5cb85c' : (($row['status']=='Rejected') ? '#d9534f' : '#f0ad4e')); ?>; color: white; padding: 4px 8px; border-radius: 4px;"><?php echo $row['status']; ?></span>
                </td>
                <td>
                    <?php if($row['status'] == 'Pending'): ?>
                    <a href="requests.php?action=approve&id=<?php echo $row['id']; ?>" class="btn"
                        style="padding: 5px 10px; font-size: 0.8rem; background: #5cb85c; text-decoration: none;">Approve</a>
                    <a href="requests.php?action=reject&id=<?php echo $row['id']; ?>" class="btn"
                        style="padding: 5px 10px; font-size: 0.8rem; background: #d9534f; text-decoration: none;">Reject</a>
                    <?php elseif($row['status'] == 'Approved'): ?>
                    <a href="requests.php?action=dispatch&id=<?php echo $row['id']; ?>" class="btn"
                        style="padding: 5px 10px; font-size: 0.8rem; background: #0275d8; text-decoration: none;">Dispatch</a>
                    <?php else: ?>
                    <em>Closed</em>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>