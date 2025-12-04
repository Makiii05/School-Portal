<?PHP
$sql="SELECT * FROM audit_trail AT JOIN users U ON AT.user_id = U.id ORDER BY AT.datetime DESC";
$result = $conn->query($sql);
?>
<div class="modal fade" id="auditTrailModal" tabindex="-1" aria-labelledby="auditTrailLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header sticky">
                <h1 class="modal-title fs-5" id="auditTrailLabel">Audit Trail</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body overflow-y-scroll">
                <table class="table table-striped table-hover overflow-scroll">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">User</th>
                            <th scope="col">Reference No.</th>
                            <th scope="col">Action</th>
                            <th scope="col">Date & Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 1;
                        while($row = $result->fetch_assoc()){
                            $action_class = $row["action"] == 'A' ? 'bg-success-subtle text-success' : ($row["action"] == 'E' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger');
                            echo "
                            <tr>
                                <th scope='row'>$count</th>
                                <td>$row[user]</td>
                                <td>$row[ref_no]</td>
                                <td class='$action_class text-center fw-bolder'>$row[action]</td>
                                <td>$row[datetime]</td>
                            </tr>
                            ";
                            $count++;
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