<?php
if (!isset($conn)) {
    include 'db_connect.php';
}
?>

<div class="col-lg-12">
    <div class="card card-outline card-primary">
        <div class="card-body">
            <form action="" id="manage-project" method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars(isset($id) ? $id : '', ENT_QUOTES); ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="project-name" class="control-label">Name</label>
                            <input type="text" id="project-name" class="form-control form-control-sm" name="name" required value="<?php echo htmlspecialchars(isset($name) ? $name : '', ENT_QUOTES); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status" class="control-label">Status</label>
                            <select name="status" id="status" class="custom-select custom-select-sm" required>
                                <option value="0" <?php echo (isset($status) && $status == 0) ? 'selected' : ''; ?>>Pending</option>
                                <option value="3" <?php echo (isset($status) && $status == 3) ? 'selected' : ''; ?>>On-Hold</option>
                                <option value="5" <?php echo (isset($status) && $status == 5) ? 'selected' : ''; ?>>Done</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start-date" class="control-label">Start Date</label>
                            <input type="date" id="start-date" class="form-control form-control-sm" autocomplete="off" name="start_date" required value="<?php echo htmlspecialchars(isset($start_date) ? date("Y-m-d", strtotime($start_date)) : '', ENT_QUOTES); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end-date" class="control-label">End Date</label>
                            <input type="date" id="end-date" class="form-control form-control-sm" autocomplete="off" name="end_date" required value="<?php echo htmlspecialchars(isset($end_date) ? date("Y-m-d", strtotime($end_date)) : '', ENT_QUOTES); ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php if ($_SESSION['login_type'] == 1) : ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="manager-id" class="control-label">Project Manager</label>
                                <select id="manager-id" class="form-control form-control-sm select2" name="manager_id" required>
                                    <option></option>
                                    <?php 
                                    $managers = $conn->query("SELECT *, CONCAT(firstname,' ',lastname) AS name FROM users WHERE type = 2 ORDER BY name ASC");
                                    while ($row = $managers->fetch_assoc()) :
                                    ?>
                                        <option value="<?php echo htmlspecialchars($row['id'], ENT_QUOTES); ?>" <?php echo (isset($manager_id) && $manager_id == $row['id']) ? "selected" : ''; ?>><?php echo htmlspecialchars(ucwords($row['name']), ENT_QUOTES); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                    <?php else : ?>
                        <input type="hidden" name="manager_id" value="<?php echo htmlspecialchars($_SESSION['login_id'], ENT_QUOTES); ?>">
                    <?php endif; ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="team-members" class="control-label">Project Team Members</label>
                            <select id="team-members" class="form-control form-control-sm select2" multiple="multiple" name="user_ids[]">
                                <option></option>
                                <?php 
                                $employees = $conn->query("SELECT *, CONCAT(firstname,' ',lastname) AS name FROM users WHERE type = 3 ORDER BY name ASC");
                                while ($row = $employees->fetch_assoc()) :
                                ?>
                                    <option value="<?php echo htmlspecialchars($row['id'], ENT_QUOTES); ?>" <?php echo (isset($user_ids) && in_array($row['id'], explode(',', $user_ids))) ? "selected" : ''; ?>><?php echo htmlspecialchars(ucwords($row['name']), ENT_QUOTES); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10">
                        <div class="form-group">
                            <label for="description" class="control-label">Description</label>
                            <textarea id="description" name="description" cols="30" rows="10" class="summernote form-control"><?php echo htmlspecialchars(isset($description) ? $description : '', ENT_QUOTES); ?></textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer border-top border-info">
            <div class="d-flex w-100 justify-content-center align-items-center">
                <button class="btn btn-flat bg-gradient-primary mx-2" form="manage-project">Save</button>
                <button class="btn btn-flat bg-gradient-secondary mx-2" type="button" onclick="location.href='index.php?page=project_list'">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#manage-project').on('submit', function (e) {
            e.preventDefault();
            start_load();

            const formData = new FormData(this);

            $.ajax({
                url: 'ajax.php?action=save_project',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                success: function (resp) {
                    if (resp == 1) {
                        alert_toast('Data successfully saved', "success");
                        setTimeout(function () {
                            location.href = 'index.php?page=project_list';
                        }, 2000);
                    } else {
                        alert_toast('Error saving data', "error");
                    }
                },
                error: function () {
                    alert_toast('An error occurred. Please try again.', "error");
                },
                complete: function () {
                    end_load();
                }
            });
        });
    });
</script>
