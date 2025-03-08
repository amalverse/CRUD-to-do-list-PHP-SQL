<?php
// This script is for a simple note-taking web application using PHP and MySQL.
// It allows users to add, edit, and delete notes.

$insert = false;    // Variable to check if a note is inserted
$update = false;    // Variable to check if a note is updated
$delete = false;    // Variable to check if a note is deleted

// Connect to the MySQL Database 
$servername = "localhost";
$username = "root";
$password = "My-Notes@321";
$database = "notes";
// Create a connection
$conn = mysqli_connect($servername, $username, $password, $database);

// If the connection fails, we stop the script and show an error message.
if (!$conn) {
  die("Sorry we failed to connect: " . mysqli_connect_error());
}

// If a note is to be deleted, we get its ID from the URL and delete it from the database.
if (isset($_GET['delete'])) {
  $sno = $_GET['delete'];
  $delete = true;
  $sql = "DELETE FROM `notes` WHERE `sno` = $sno";
  $result = mysqli_query($conn, $sql);
}

// If the form is submitted (POST request), we either update an existing note or add a new one.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['snoEdit'])) {
    // Update the record with given ID
    $sno = $_POST["snoEdit"];
    $title = $_POST["titleEdit"];
    $description = $_POST["descriptionEdit"];

    // Sql query to be executed to update the record
    $sql = "UPDATE `notes` SET `title` = '$title' , `description` = '$description' WHERE `notes`.`sno` = $sno";
    $result = mysqli_query($conn, $sql);
    if ($result) {
      $update = true;
    } else {
      echo "We could not update the record successfully";
    }
  } else {
    // Add a new note to the database.
    $title = $_POST["title"];
    $description = $_POST["description"];
    // Sql query to be executed to add a new note
    $sql = "INSERT INTO `notes` (`title`, `description`) VALUES ('$title', '$description')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
      $insert = true;
    } else {
      echo "The record was not inserted successfully because of this error ---> " . mysqli_error($conn);
    }
  }
}
?>

<!-- <!doctype html> for page structure -->
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">


  <title>My Notes</title>

</head>

<body>


  <!-- Modal for editing notes -->
  <!-- This is a Bootstrap Modal (a pop-up box) for editing a note -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">

    <!-- This div defines the overall structure of the modal -->
    <div class="modal-dialog" role="document">

      <!-- This div contains the actual content of the modal -->
      <div class="modal-content">

        <!-- Modal Header: Displays the title and close button -->
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit this Note</h5>
          <!-- Close button (×) to close the modal -->
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>

        <!-- Form that sends edited note data to "index.php" using the POST method -->
        <form action="/crud/index.php" method="POST">

          <!-- Modal Body: Contains input fields for editing a note -->
          <div class="modal-body">
            <!-- A hidden input to store the note's serial number (used for identifying which note is being edited) -->
            <input type="hidden" name="snoEdit" id="snoEdit">

            <!-- Input field for editing the note's title -->
            <div class="form-group">
              <label for="title">Note Title</label>
              <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp">
            </div>

            <!-- Textarea field for editing the note's description -->
            <div class="form-group">
              <label for="desc">Note Description</label>
              <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3"></textarea>
            </div>
          </div>

          <!-- Modal Footer: Contains buttons to save or close the modal -->
          <div class="modal-footer d-block mr-auto">
            <!-- Button to close the modal without saving changes -->
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <!-- Button to save changes by submitting the form -->
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>

        </form>
      </div>
    </div>
  </div>


  <!-- Navigation bar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">My-Notes</a>

  </nav>

  <!-- Alerts for success messages -->
  <?php
  if ($insert) {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your note has been inserted successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
  <?php
  if ($delete) {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your note has been deleted successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
  <?php
  if ($update) {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your note has been updated successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>

  <!-- Form to add a new note -->
  <div class="container my-4">
    <h2>Add a Note to My-Notes</h2>
    <form action="/crud/index.php" method="POST">
      <div class="form-group">
        <label for="title">Note Title</label>
        <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
      </div>

      <div class="form-group">
        <label for="desc">Note Description</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Add Note</button>
    </form>
  </div>

  <div class="container my-4">

    <!-- Table to display notes -->
    <table class="table" id="myTable">
      <thead>
        <tr>
          <th scope="col">S.No</th>
          <th scope="col">Title</th>
          <th scope="col">Description</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>

        <?php
        $sql = "SELECT * FROM `notes`";            // Sql query to get all notes from the database 
        $result = mysqli_query($conn, $sql);
        $sno = 0;
        while ($row = mysqli_fetch_assoc($result)) {          // Fetching notes from the database
          $sno = $sno + 1; // Incrementing the serial number
          // Displaying the notes in a table
          echo "<tr>
            <th scope='row'>" . $sno . "</th>
            <td>" . $row['title'] . "</td>
            <td>" . $row['description'] . "</td>
            <td> <button class='edit btn btn-sm btn-primary' id=" . $row['sno'] . ">Edit</button> 
                 <button class='delete btn btn-sm btn-primary' id=d" . $row['sno'] . ">Delete</button> 
            </td>
          </tr>";
        }
        // d is added to the id to differentiate between edit and delete buttons
        // d is removed using substr() in the delete button JavaScript code 
        // sno is the serial number of the note
        ?>


      </tbody>
    </table>
  </div>
  <hr>

  <!-- JavaScript for DataTables and Bootstrap -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
    crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#myTable').DataTable();

    });
  </script>
  <script>
    // JavaScript to handle edit button clicks
    edits = document.getElementsByClassName('edit'); // Getting all edit buttons
    Array.from(edits).forEach((element) => { // Adding event listener to each edit button
      element.addEventListener("click", (e) => { // When edit button is clicked
        console.log("edit "); // Display a confirmation box
        tr = e.target.parentNode.parentNode; // Get the row of the note to be edited
        title = tr.getElementsByTagName("td")[0].innerText; // Get the title of the note
        description = tr.getElementsByTagName("td")[1].innerText; // Get the description of the note
        // Get the serial number of the note to be edited
        console.log(title, description); // Log the title and description
        titleEdit.value = title; // Set the title and description in the form
        descriptionEdit.value = description;
        snoEdit.value = e.target.id; // Set the serial number in the hidden input field
        console.log(e.target.id) // Log the serial number
        $('#editModal').modal('toggle'); // Toggle the modal
      })
    })
    //e.target.id is used to get the id of the edit button

    // JavaScript to handle delete button clicks
    deletes = document.getElementsByClassName('delete'); // Getting all delete buttons
    Array.from(deletes).forEach((element) => { // Adding event listener to each delete button
      element.addEventListener("click", (e) => { // When delete button is clicked
        console.log("edit "); // Display a confirmation box
        sno = e.target.id.substr(1); // Get the serial number of the note to be deleted
        //e.target.id.substr(1) is used to remove the d from the id of the delete button

        // If the user confirms, redirect to the index.php page with the delete parameter set to the serial number of the note to be deleted
        if (confirm("Are you sure you want to delete this note!")) {
          console.log("yes");
          window.location = `/crud/index.php?delete=${sno}`;
        } else {
          console.log("no");
        }
      })
    })
  </script>
</body>

</html>