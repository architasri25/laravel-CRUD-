<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Creation</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 500px;
            width: 100%;
        }

        .form-container h3 {
            text-align: center;
            color: #333333;
            margin-bottom: 20px;
        }

        .form-container label {
            font-weight: bold;
            margin-bottom: 5px;
            display: inline-block;
            color: #555555;
        }

        .form-container input[type="text"],
        .form-container input[type="file"],
        .form-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            color: #333333;
        }

        .form-container input[type="text"]:focus,
        .form-container input[type="file"]:focus,
        .form-container select:focus {
            border-color: #007BFF;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .form-container button {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            border: none;
            color: #ffffff;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        #output {
            margin-top: 20px;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h3>Add New Student</h3>
        <form id="my-form">
            @csrf
            <!-- Name -->
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter Name" required>
            </div>

            <!-- Description -->
            <div>
                <label for="description">Description:</label>
                <input type="text" id="description" name="description" placeholder="Enter Description" required>
            </div>

            <!-- Image -->
            <div>
                <label for="image">Image:</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>

            <!-- Status -->
            <div>
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" id="btnSubmit">Add Student</button>
            </div>
        </form>
        <div id="output"></div>
    </div>

    <script>
        $(document).ready(function () {
            $("#my-form").submit(function (event) {
                event.preventDefault();

                var form = $("#my-form")[0];
                var data = new FormData(form);

                $("#btnSubmit").prop("disabled", true);

                $.ajax({
                    type: "POST",
                    url: "{{ route('addStudent') }}",
                    data: data,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $("#output").text(data.res).css("color", "green");
                        $("#btnSubmit").prop("disabled", false);
                        $("#my-form")[0].reset();
                    },
                    error: function (e) {
                        $("#output").text(e.responseText).css("color", "red");
                        $("#btnSubmit").prop("disabled", false);
                    }
                });
            });
        });
    </script>
</body>
</html>
