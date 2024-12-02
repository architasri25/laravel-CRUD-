<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Students Data</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        h1 {
            text-align: center;
            margin: 20px 0;
            font-size: 2rem;
            color: #4CAF50;
        }

        #output {
            display: block;
            text-align: center;
            margin: 10px 0;
            font-size: 1rem;
            color: #ff5722;
        }

        /* Table Styling */
        #students-table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        #students-table th, #students-table td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
        }

        #students-table th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
        }

        #students-table td img {
            border-radius: 8px;
        }

        #students-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #students-table tr:hover {
            background-color: #f1f9f1;
        }

        /* Action Buttons */
        #students-table a {
            display: inline-block;
            padding: 5px 10px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        #students-table a:hover {
            opacity: 0.8;
        }

        #students-table a:nth-child(1) {
            background-color: #ff9800; /* Edit Button */
        }

        #students-table a:nth-child(2) {
            background-color: #f44336; /* Delete Button */
        }

        /* Responsive Table */
        @media screen and (max-width: 768px) {
            #students-table {
                font-size: 14px;
            }

            #students-table th, #students-table td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <h1>Students Data</h1>
    <span id="output"></span>
    <table id="students-table">
        <tr>
            <th>S.No</th>
            <th>Name</th>
            <th>Description</th>
            <th>Image</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </table>

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
                            $("#students-table").append(`
                                <tr id="row-${student.id}">
                                    <td>${index + 1}</td>
                                    <td>${student.name}</td>
                                    <td>${student.description}</td>
                                    <td>
                                        <img src="{{ asset('storage/${image}') }}" alt="${image}" width="100px" height="100px"/>
                                    </td>
                                    <td>${student.status === 1 ? 'Active' : 'Inactive'}</td>
                                    <td>
                                        <a href="editUser/${student.id}">Edit</a>
                                        <a href="#" class="deleteData" data-id="${student.id}">Delete</a>
                                    </td>
                                </tr>
                            `);
                        });
                    } else {
                        $("#students-table").append("<tr><td colspan='6'>Data Not Found</td></tr>");
                    }
                },
                error: function (err) {
                    console.error(err.responseText);
                }
            });

            // Handle Delete Button Click
            $("#students-table").on("click", ".deleteData", function (e) {
                e.preventDefault();
                var id = $(this).attr("data-id");

                if (confirm("Are you sure you want to delete this record?")) {
                    $.ajax({
                        type: "DELETE",
                        url: `/delete-data/${id}`,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                            if (data.result === 'Student deleted') {
                                $("#row-" + id).remove(); // Remove the row from table
                                $("#output").text(data.result); // Display success message
                            }
                        },
                        error: function (err) {
                            console.error(err.responseText);
                            alert("Error occurred while deleting the record.");
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
