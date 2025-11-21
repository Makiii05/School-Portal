<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="searchModalLabel">Search Student</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">Search By</label>
                <div class="d-flex gap-3">
                    <select id="search_by" class="form-select border-dark">
                        <option value="studno">Student #</option>
                        <option value="name">Student Name</option>
                    </select>
                    <input type="text" id="search_by_input" class="form-control border-dark" placeholder="Enter Student Information">
                </div>
                <hr>
                <table class="table table-striped table-hover overflow-scroll">
                    <thead class="table-dark">
                        <tr>
                            <th>Student#</th>
                            <th>Student Name</th>
                            <th>Course</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="student_list_table">
                        <?php
                        if ($student_list_query->num_rows > 0) {
                            while ($row=$student_list_query->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>$row[studno]</td>";
                                echo "<td>$row[name]</td>";
                                echo "<td>$row[course_name]</td>";
                                echo "<td><form action='collection.php' method='POST'>
                                        <input type='hidden' name='student_no' value='$row[studno]'>
                                        <button type='submit' class='btn border-dark'><i class='bi bi-journal-plus p-1'></i></button>
                                    </form></td>";
                                echo "</tr>";
                                $student_list[] = $row;
                            }
                        } else {
                            echo '<tr><td colspan="4" class="text-center text-muted">Currently No Information</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>