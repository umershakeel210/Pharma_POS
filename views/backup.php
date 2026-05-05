<?php
include '../config/auth.php';
include '../config/admin_auth.php';

$backupMessage = '';
$backupDir = __DIR__ . '/../backups/';

if (!is_dir($backupDir)) {
    mkdir($backupDir, 0777, true);
}

// Create backup
if (isset($_POST['backup'])) {

    $dbHost = 'localhost';
    $dbUser = 'root';
    $dbPass = '';
    $dbName = 'pharma_pos';

    $fileName = 'pharma_pos_backup_' . date('Y-m-d_H-i-s') . '.sql';
    $backupFile = $backupDir . $fileName;

    $mysqldumpPath = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';

    if (!file_exists($mysqldumpPath)) {
        $backupMessage = "mysqldump.exe not found at: " . $mysqldumpPath;
    } else {
        if ($dbPass == '') {
            $command = "\"$mysqldumpPath\" --user=$dbUser --host=$dbHost $dbName > \"$backupFile\" 2>&1";
        } else {
            $command = "\"$mysqldumpPath\" --user=$dbUser --password=$dbPass --host=$dbHost $dbName > \"$backupFile\" 2>&1";
        }

        exec($command, $output, $result);

        if ($result === 0 && file_exists($backupFile) && filesize($backupFile) > 0) {
            $backupMessage = "Backup created successfully.";
        } else {
            $backupMessage = "Backup failed.";
        }
    }
}

// Delete backup
if (isset($_GET['delete'])) {
    $deleteFile = basename($_GET['delete']);
    $deletePath = $backupDir . $deleteFile;

    if (file_exists($deletePath)) {
        unlink($deletePath);
        header("Location: backup.php?msg=deleted");
        exit;
    }
}

// Download backup
if (isset($_GET['download'])) {
    $downloadFile = basename($_GET['download']);
    $downloadPath = $backupDir . $downloadFile;

    if (file_exists($downloadPath)) {
        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="' . $downloadFile . '"');
        header('Content-Length: ' . filesize($downloadPath));
        readfile($downloadPath);
        exit;
    }
}

if (isset($_GET['msg']) && $_GET['msg'] == 'deleted') {
    $backupMessage = "Backup deleted successfully.";
}

$backupFiles = glob($backupDir . '*.sql');
rsort($backupFiles);

include '../views/layouts/header.php';
?>

<div class="container mt-4">
    <h3>Database Backup</h3>

    <?php if (!empty($backupMessage)): ?>
        <div class="alert alert-info">
            <?php echo $backupMessage; ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="mb-3">
        <button type="submit" name="backup" class="btn btn-primary">
            Create New Backup
        </button>
    </form>

    <div class="card">
        <div class="card-header">
            Backup Files
        </div>

        <div class="card-body">
            <?php if (empty($backupFiles)): ?>
                <p>No backup files found.</p>
            <?php else: ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>File Name</th>
                            <th>Size</th>
                            <th>Created At</th>
                            <th width="180">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($backupFiles as $index => $file): ?>
                            <?php
                                $fileName = basename($file);
                                $fileSize = round(filesize($file) / 1024, 2) . ' KB';
                                $createdAt = date('d M Y h:i A', filemtime($file));
                            ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo $fileName; ?></td>
                                <td><?php echo $fileSize; ?></td>
                                <td><?php echo $createdAt; ?></td>
                                <td>
                                    <a href="backup.php?download=<?php echo urlencode($fileName); ?>" class="btn btn-sm btn-success">
                                        Download
                                    </a>

                                    <a href="backup.php?delete=<?php echo urlencode($fileName); ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure you want to delete this backup?');">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../views/layouts/footer.php'; ?>