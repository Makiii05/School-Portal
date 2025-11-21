<?PHP
require("../conn.php");
require("../sql/check_admin.php");

$last_OR = $conn->query("SELECT or_number FROM collections ORDER BY collection_id DESC LIMIT 1")->fetch_assoc();
$date = date("Y-m-d");
$studentNo = "";
$cash = "";
$gcash = "";
$refNo = "";
$semSelected = "";
$semesters = "";
$collections = $conn->query("SELECT 
    c.*,
    s.student_no AS student_no,
    sem.code AS semester_code
    FROM collections c
    JOIN students s ON c.student_id = s.student_id
    JOIN semesters sem ON c.semester_id = sem.semester_id
    ORDER BY c.or_date DESC");
    
if(isset($_POST['student_no'])){
    $checkIfExisting = $conn->query("SELECT * FROM students WHERE student_no = '$_POST[student_no]'");
    if(mysqli_num_rows($checkIfExisting) == 0){
        header("location:../admin/collection.php?error=4");
        exit;
    }
    $semesters = $conn->query("SELECT sem.semester_id, sem.code
        FROM semesters sem
        RIGHT JOIN student_subjects ss ON ss.semester_id = sem.semester_id
        JOIN students s ON ss.student_id = s.student_id
        WHERE s.student_no = '$_POST[student_no]'
        GROUP BY sem.code
    ");
    $collections = $conn->query("SELECT 
        c.*,
        s.student_no AS student_no,
        sem.code AS semester_code
        FROM collections c
        JOIN students s ON c.student_id = s.student_id
        JOIN semesters sem ON c.semester_id = sem.semester_id
        WHERE s.student_no = '$_POST[student_no]'
        ORDER BY c.or_date DESC");
}

if(isset($_POST['or_number'])){
    $transaction = $conn->query("SELECT * FROM collections WHERE or_number = '$_POST[or_number]'");
    if(mysqli_num_rows($transaction) >= 1){
        $trans = $transaction->fetch_assoc();
        $date = $trans['or_date'];
        $studentNo = $conn->query("SELECT student_no FROM students WHERE student_id = '$trans[student_id]'")->fetch_assoc()['student_no'];
        $semesters = $conn->query("SELECT sem.semester_id, sem.code
            FROM semesters sem
            RIGHT JOIN student_subjects ss ON ss.semester_id = sem.semester_id
            JOIN students s ON ss.student_id = s.student_id
            WHERE s.student_no = '$studentNo'
            GROUP BY sem.code
        ");
        $semSelected = $trans['semester_id'];
        $cash = $trans['cash'] == "0.00" ? "" : $trans['cash'];
        $gcash = $trans['gcash'] == "0.00" ? "" : $trans['gcash'];
        $refNo = $trans['gcash_refno'];
    }
}

$student_list_query = $conn->query("
    SELECT 
        st.student_id AS id, 
        st.student_no AS studno,
        st.name AS name, 
        st.gender AS gender, 
        cr.name AS course_name 
    FROM students st 
    JOIN courses cr ON st.course_id = cr.course_id
");
$student_list = [];
?>
<!DOCTYPE html>
<html lang="en">
<?PHP 
require("../components/head.php");
?>
<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">

            <?PHP
            require("../components/sidebar.php");
            require("../components/search_modal_collection.php");
            ?>
            <div class="col py-3">
                
                <div class="d-flex w-100">
                    <h3>Collections</h3>
                    <div class="w-100 text-end mb-3">
                        <p><b>Active User:  </b><?= $_SESSION['user_name'] ?></p>
                    </div>
                </div>
                <hr>
                <div class="d-flex">
                    <div class="d-flex flex-grow-1">
                        <form method="POST" action="../sql/controller.php" class="w-50 p-4">
                            <div class="d-flex gap-2">
                                <div class="flex-grow-1">
                                    <label>Official Reciept #</label>
                                    <input class="form-control mb-3" id="or_number" name="or_number" value="<?= $_POST["or_number"] ?? $last_OR["or_number"] + 1?>" autofocus required type="text">
                                </div>
                                <div>
                                    <label>Date</label>
                                    <input class="form-control mb-3" name="date" required type="date" value="<?= $date ?>">
                                </div>
                            </div>
                            <div>
                                <label>Student#</label>
                                <div class="d-flex gap-3">
                                    <input class="form-control mb-3" name="student_no" id="student_no" required type="text" value="<?= $_POST['student_no'] ?? $studentNo?>">
                                    <button type="button" class="btn btn-dark mb-3" data-bs-toggle="modal" data-bs-target="#searchModal">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>                            
                            </div>
                            <div>
                                <label>Semester</label>
                                <div class="d-flex gap-3">
                                    <select class="form-select mb-3" id="semester_id" required name="semester_id">
                                    <?php 
                                    if(!empty($semesters)):
                                    while ($sem = $semesters->fetch_assoc()):?>
                                        <option value="<?= $sem['semester_id'] ?>" <?= $semSelected == $sem['semester_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($sem['code']) ?>
                                        </option>
                                    <?php endwhile; 
                                    endif;?>
                                    </select>
                                    <button type="button" onclick="printFormFunction()" class="btn bg-dark text-light w-25 mb-3" <?= $semesters ? '' : 'disabled' ?>>Ledger</button>
                                </div>
                            </div>
                            <div>
                                <label>Cash</label>
                                <input class="form-control mb-3" name="cash" id="cash" type="text" value="<?= $cash ?>">
                            </div>
                            <div>
                                <label>Gcash</label>
                                <input class="form-control mb-3" name="gcash" type="number" value="<?= $gcash ?>">
                                <label>Reference #</label>
                                <input class="form-control mb-3" name="reference_no" type="number" value="<?= $refNo ?>">
                            </div>
                            <div>
                                <input type="submit" class="form-control bg-success text-light w-25" name="add" value="Add Data">
                            </div>
                        </form>
                        <form action="collection.php" method="POST" id="hidden_form">
                            <input type="hidden" id="hidden_student_no" name="student_no" value="">
                        </form>
                        <form action="collection.php" method="POST" id="or_hidden_form">
                            <input type="hidden" id="hidden_or_number" name="or_number" value="">
                        </form>
                        <form action="../print/p_stud_ledger.php" method="POST" id="print_form" target="_blank">
                            <input type="hidden" id="print_student_no" name="student_no">
                            <input type="hidden" id="print_semester_id" name="semester_id">
                        </form>
                        <div class="w-50 p-4">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr><th class="text-center" colspan=5>RECENT STUDENT TRANSACTION</th></tr>
                                    <tr><th scope="col">OR#</th><th scope="col">Student#</th><th scope="col">Semester</th><th scope="col">Date</th><th scope="col">Action</th></tr>
                                </thead>
                                <tbody id="student_list_table">
                                    <?PHP
                                    while($row=$collections->fetch_assoc()){
                                        echo "<tr>";
                                        echo "
                                        <td>$row[or_number]</td>
                                        <td>$row[student_no]</td>
                                        <td>$row[semester_code]</td>
                                        <td>$row[or_date]</td>
                                        <td>
                                            <form action='collection.php' method='POST'>
                                                <input type='hidden' name='or_number' value='$row[or_number]'>
                                                <button type='submit' class='btn border-dark'><i class='bi bi-pencil p-1 text-'></i></button>
                                            </form>
                                        </td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    const studentList = <?= json_encode($student_list) ?>;
    const table = document.getElementById("student_list_table");
    const searchBy = document.getElementById("search_by");
    const searchByInput = document.getElementById("search_by_input");

    // Elements
    const studentInput = document.getElementById("student_no");
    const hiddenStudentNo = document.getElementById("hidden_student_no");
    const hiddenForm = document.getElementById("hidden_form");
    const semId = document.getElementById("semester_id");
    const cashInput = document.getElementById("cash");

    // Print Form
    const printStudentNo = document.getElementById("print_student_no");
    const printSemesterId = document.getElementById("print_semester_id");
    const printForm = document.getElementById("print_form");

    const hiddetOrNumber = document.getElementById("hidden_or_number")
    const orHiddenForm = document.getElementById("or_hidden_form")
    const orNumberInput = document.getElementById("or_number");

    orNumberInput.onchange = orNumberFunction;

    studentInput.onchange = find_semester;

    searchBy.onchange = search_student;
    searchByInput.oninput = search_student;

    async function updateBalance() {
        const studentNo = studentInput.value;
        const semesterId = semId.value;

        if (!studentNo || !semesterId) return;

        try {
            const res = await fetch("../components/get_balance.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ student_no: studentNo, semester_id: semesterId })
            });

            const data = await res.json();
            if (data.success) {
                cashInput.placeholder = data.balance;
            } else {
                cashInput.placeholder = "";
                console.warn(data.message || "Balance not found");
            }
        } catch (err) {
            console.error(err);
        }
    }

    // Update balance when either student or semester changes
    semId.addEventListener("change", updateBalance);
    studentInput.addEventListener("change", updateBalance);
    // Auto-update balance if student_no was posted (e.g., after search)
    <?php if (isset($_POST['student_no'])): ?>
        document.addEventListener("DOMContentLoaded", () => {
            updateBalance();
        });
    <?php endif; ?>

    function find_semester() {
        hiddenStudentNo.value = studentInput.value;
        hiddenForm.submit();
    }

    function orNumberFunction() {
        hiddetOrNumber.value = orNumberInput.value;
        orHiddenForm.submit();
    }

    function printFormFunction() {
        printStudentNo.value = studentInput.value;
        printSemesterId.value = semId.value;
        printForm.submit();
    }

    function search_student() {
        const filterBy = searchBy.value;
        const keyword = searchByInput.value.toLowerCase();

        table.innerHTML = "";

        const results = studentList.filter(stud =>
            stud[filterBy].toLowerCase().includes(keyword)
        );

        if (results.length === 0) {
            table.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No matching students</td></tr>';
            return;
        }

        results.forEach(stud => {
            const row = `
                <tr>
                    <td>${stud.studno}</td>
                    <td>${stud.name}</td>
                    <td>${stud.course_name}</td>
                    <td>
                        <form action="collection.php" method="POST">
                            <input type="hidden" name="student_no" value="${stud.studno}">
                            <button type="submit" class="btn">
                                <i class="bi bi-journal-plus p-1"></i>
                            </button>
                        </form>
                    </td>
                </tr>`;
            table.insertAdjacentHTML("beforeend", row);
        });
    }
</script>
</html>