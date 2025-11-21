<?PHP
$gradeOptions = [
    '1.00', '1.25', '1.50', '1.75', '2.00',
    '2.25', '2.50', '2.75', '3.00', '4.00', '5.00'
];
?>

<!-- Unique Modal per Subject -->
<div class="modal fade" id="modal<?= $row['subject_id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="add.php?success=2" method="post">
                <div class="modal-header">
                    <h3><?= htmlspecialchars($student_info['name']) ?>’s Grade</h3>
                </div>
                <div class="modal-body">
                    <b>Subject Code</b>: <p><?= htmlspecialchars($row['code']) ?></p>
                    <hr>
                    <input type="hidden" name="student_id" value="<?= $student_id ?>">
                    <input type="hidden" name="semester_id" value="<?= $semester_id ?>">
                    <input type="hidden" name="subject_id" value="<?= $row['subject_id'] ?>">

                    <!-- MIDTERM -->
                    <b>Midterm Grade</b>
                    <select name="midterm" class="form-select">
                        <option value="Null">Select Grade (Null)</option>
                        <?php 
                        $midtermValue = $row['midterm']; // value from DB
                        foreach ($gradeOptions as $grade):
                            $selected = ($midtermValue == $grade) ? 'selected' : '';
                        ?>
                            <option value="<?= $grade ?>" <?= $selected ?>><?= $grade ?></option>
                        <?php endforeach; ?>
                    </select>

                    <!-- FINAL COURSE GRADE -->
                    <b>Final Course Grade</b>
                    <select name="fcg" class="form-select">
                        <option value="Null">Select Grade (Null)</option>
                        <?php 
                        $fcgValue = $row['fcg']; // value from DB
                        foreach ($gradeOptions as $grade):
                            $selected = ($fcgValue == $grade) ? 'selected' : '';
                        ?>
                            <option value="<?= $grade ?>" <?= $selected ?>><?= $grade ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="createGrade" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
