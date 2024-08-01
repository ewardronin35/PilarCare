<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .container {
            display: flex;
            flex-direction: row;
            min-height: 100vh;
        }

        .sidebar {
            width: 80px;
            background-color: #00d2ff;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            transition: width 0.3s ease-in-out;
            overflow: hidden;
            z-index: 1000;
        }

        .sidebar:hover {
            width: 250px;
        }

        .main-content {
            margin-left: 80px;
            width: calc(100% - 80px);
            padding: 20px;
            transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
        }

        .sidebar:hover ~ .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
        }

        .table-container {
            margin-top: 20px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.5s ease-in-out;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
        }

        .table th, .table td {
            padding: 15px;
            text-align: left;
        }

        .table th {
            background-color: #007bff;
            color: white;
        }

        .table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table tr:hover {
            background-color: #ddd;
        }

        .table td button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .table td button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .table td button:active {
            transform: scale(0.95);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 10px;
            animation: fadeInUp 0.5s ease-in-out;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            margin-top: 10px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-group button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .form-group button:active {
            transform: scale(0.95);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

    </style>

   
<div class="container">
        <main class="main-content">
            <h1>Health Examinations</h1>

            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Health Examination Picture</th>
                            <th>X-ray Picture</th>
                            <th>Lab Result Picture</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($healthExaminations as $examination)
                            <tr>
                                <td>
                                    <img src="{{ asset('storage/' . $examination->health_examination_picture) }}" alt="Health Examination Picture" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
                                </td>
                                <td>
                                    <img src="{{ asset('storage/' . $examination->xray_picture) }}" alt="X-ray Picture" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
                                </td>
                                <td>
                                    <img src="{{ asset('storage/' . $examination->lab_result_picture) }}" alt="Lab Result Picture" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
                                </td>
                                <td>
                                    <button onclick="openEditModal({{ $examination->id }})">Edit</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Edit Modal -->
            <div id="editModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeEditModal()">&times;</span>
                    <h2>Edit Health Examination</h2>
                    <form id="edit-form" method="POST" action="{{ route('student.health-examination.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="examination_id" name="examination_id">
                        
                        <div class="form-group">
                            <label for="health_examination_picture">Health Examination Picture</label>
                            <label for="health_examination_picture" class="button">Select Picture</label>
                            <input type="file" id="health_examination_picture" name="health_examination_picture" accept="image/*">
                        </div>
                        
                        <div class="form-group">
                            <label for="xray_picture">X-ray Picture</label>
                            <label for="xray_picture" class="button">Select Picture</label>
                            <input type="file" id="xray_picture" name="xray_picture" accept="image/*">
                        </div>
                        
                        <div class="form-group">
                            <label for="lab_result_picture">Lab Result Picture</label>
                            <label for="lab_result_picture" class="button">Select Picture</label>
                            <input type="file" id="lab_result_picture" name="lab_result_picture" accept="image/*">
                        </div>
                        
                        <div class="form-group">
                            <button type="submit">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function openEditModal(id) {
            document.getElementById('examination_id').value = id;
            document.getElementById('editModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        document.getElementById('edit-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch('{{ route('student.health-examination.update') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Health examination updated successfully.'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error updating the health examination.'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'There was an error updating the health examination.'
                });
                console.error('Error:', error);
            });
        });
    </script>
</x-app-layout>