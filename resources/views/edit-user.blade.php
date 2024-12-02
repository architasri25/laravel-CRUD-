<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
<div class="container my-5">
    <h2 class="text-center mb-4">Update Student</h2>

    <!-- Display current student image -->
    <div class="text-center mb-4">
        <img src="{{ asset('storage/' . $student->image) }}" alt="Student Image" class="img-thumbnail" width="150">
    </div>

    <!-- Update Form -->
    <form id="update-form" action="{{ route('updateStudent') }}" method="POST" enctype="multipart/form-data" class="border p-4 rounded bg-light shadow">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" name="name" value="{{ $student->name }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description:</label>
            <input type="text" class="form-control" name="description" value="{{ $student->description }}" required>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Image:</label>
            <input type="file" class="form-control" name="image">
            <input type="hidden" name="id" value="{{ $student->id }}">
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status:</label>
            <select name="status" class="form-select">
                <option value="1" {{ $student->status ? 'selected' : '' }}>Active</option>
                <option value="0" {{ !$student->status ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Update Data</button>
        </div>
    </form>

    <div id="output" class="text-center mt-3"></div>

    <!-- Student Table -->
    <h3 class="text-center my-4">Students Data</h3>
    <table class="table table-bordered table-striped" id="students-table">
        <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Description</th>
            <th>Image</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <!-- Rows will be appended dynamically -->
        </tbody>
    </table>
</div>

<!-- JavaScript -->
<script>
    $(document).ready(function () {
        // Fetch students data via AJAX
        $.ajax({
            type: "GET",
            url: "{{ route('getStudents') }}",
            success: function (data) {
                if (data.students && data.students.length) {
                    data.students.forEach((student, index) => {
                        let image = student.image;
                        $("#students-table tbody").append(`
                            <tr id="row-${student.id}">
                                <td>${index + 1}</td>
                                <td>${student.name}</td>
                                <td>${student.description}</td>
                                <td>
                                    <img src="{{ asset('storage/${image}') }}" alt="${image}" class="img-thumbnail" width="100">
                                </td>
                                <td>${student.status ? 'Active' : 'Inactive'}</td>
                                <td>
                                    <a href="editUser/${student.id}" class="btn btn-sm btn-warning">Edit</a>
                                    <button class="btn btn-sm btn-danger deleteData" data-id="${student.id}">Delete</button>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    $("#students-table tbody").append("<tr><td colspan='6' class='text-center'>Data Not Found</td></tr>");
                }
            },
            error: function (err) {
                console.log(err.responseText);
            }
        });

        // Delete data with confirmation
        $("#students-table").on("click", ".deleteData", function (e) {
            e.preventDefault(); // Prevent default link behavior

            if (!confirm("Are you sure you want to delete this record?")) {
                return; // Exit if user cancels
            }

            var id = $(this).attr("data-id");
            var rowId = `#row-${id}`;

            $.ajax({
                type: "GET",
                url: `delete-data/${id}`,
                success: function (data) {
                    if (data.success) {
                        $(rowId).remove();
                        $("#output").html(`<div class="alert alert-success">${data.result}</div>`);
                    } else {
                        alert("Failed to delete the record. Please try again.");
                    }
                },
                error: function (err) {
                    console.log(err.responseText);
                    alert("Error occurred. Check console for details.");
                }
            });
        });
    });
</script>
</body>
</html>
