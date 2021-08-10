<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MLP To-Do</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body class="bg-light">

    <div class="p-5">
        <img src="{{ asset('assets/logo.png') }}">

        <div class="container mt-5">
            <div class="row">
                <div class="col-6">
                    <form>
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="task_name" placeholder="Insert task name">
                        </div>
                        <button class="btn btn-primary w-100 add-task-btn">Submit</button>
                    </form>
                </div>
                <div class="col-6">
                    <table class="table todo-task-list">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Task</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <footer class="mt-5">
            <p class="text-center text-secondary fs-6">Copyright © 2020 All Rights Reserved.</p>
        </footer>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            retrieveTasks();

            $('.add-task-btn').click(function(event) {
                event.preventDefault();

                let taskname = $('#task_name').val();
                if (taskname === '') {
                    Swal.fire('Validation', 'Please make sure the task name is provided', 'error');
                    return;
                }

                taskHandler(taskname);
            });

            function taskHandler(taskname) {
                $.ajax({
                    url: 'task-handler',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        task_name: taskname,
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.status !== 'COMPLETE') {
                            Swal.fire('Validation Error',
                                response && response.message ? response
                                .message : 'Failed to update the communication', 'error');
                            return;
                        }

                        retrieveTasks();
                        $('#task_name').val('');
                    },
                    error: function(data) {
                        if (data.responseJSON) {

                            let errorMessage = '';
                            $.each(data.responseJSON.errors.action, function(index, value) {
                                errorMessage += value;
                            });

                            Swal.fire('Validation Error',
                                errorMessage, 'error');
                        }
                    }
                });
            }

            function retrieveTasks() {
                $(".todo-task-list tbody").html('');

                $.ajax({
                    url: 'retrieve-task',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.status !== 'COMPLETE') {
                            Swal.fire('Validation Error',
                                response && response.message ? response
                                .message : 'Failed to update the communication', 'error');
                            return;
                        }

                        let tBodyContent = '';

                        $.each(response.data, function(index, row) {
                            let taskRow = '<tr>' +
                                '<th>' + ++index + '</th>';

                            if (row.status === 'complete') {
                                taskRow += '<td class="text-decoration-line-through">' + row
                                    .task + '</td>';
                            } else {
                                taskRow += '<td>' + row.task + '</td>';
                            }

                            taskRow +=
                                '<td>';

                            if (row.status === 'pending') {
                                taskRow +=
                                    '<button data-task_id="' + row.task_id +
                                    '" type="submit" class="task_complete_btn btn btn-success" style="font-size: 8px">' +
                                    '<i class="fa fa-check" aria-hidden="true"></i>' +
                                    '</button>' +
                                    '<button data-task_id="' + row.task_id +
                                    '" type="submit" class="task_delete_btn btn btn-danger" style="font-size: 8px; margin-left: 5px">' +
                                    '<i class="fa fa-times" aria-hidden="true"></i>' +
                                    '</button>';
                            }

                            taskRow +=
                                '</td>' +
                                '</tr>';

                            tBodyContent += taskRow;
                        });

                        $(".todo-task-list tbody").html(tBodyContent);

                        $('.task_delete_btn').click(function(event) {
                            let data = $(this).data();
                            deleteTask(data.task_id);
                        });

                        $('.task_complete_btn').click(function(event) {
                            let data = $(this).data();
                            completeTask(data.task_id);
                        });
                    }
                });
            }

            function deleteTask(taskID) {
                $.ajax({
                    url: 'delete-task',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        task_id: taskID,
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.status !== 'COMPLETE') {
                            Swal.fire('Validation Error',
                                response && response.message ? response
                                .message : 'Failed to update the communication', 'error');
                            return;
                        }

                        retrieveTasks();
                    },
                    error: function(data) {
                        if (data.responseJSON) {

                            let errorMessage = '';
                            $.each(data.responseJSON.errors.action, function(index, value) {
                                errorMessage += value;
                            });

                            Swal.fire('Validation Error',
                                errorMessage, 'error');
                        }
                    }
                });
            }

            function completeTask(taskID) {
                $.ajax({
                    url: 'complete-task',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        task_id: taskID,
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.status !== 'COMPLETE') {
                            Swal.fire('Validation Error',
                                response && response.message ? response
                                .message : 'Failed to update the communication', 'error');
                            return;
                        }

                        retrieveTasks();
                    },
                    error: function(data) {
                        if (data.responseJSON) {

                            let errorMessage = '';
                            $.each(data.responseJSON.errors.action, function(index, value) {
                                errorMessage += value;
                            });

                            Swal.fire('Validation Error',
                                errorMessage, 'error');
                        }
                    }
                });
            }
        });
    </script>

</body>

</html>
